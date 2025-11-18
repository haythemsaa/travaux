<?php

namespace App\Controllers;

use App\Config\Database;
use PDO;

/**
 * Payment Controller - Stripe Integration
 *
 * IMPORTANT: Install Stripe PHP library first:
 * composer require stripe/stripe-php
 *
 * Get your Stripe keys from: https://dashboard.stripe.com/apikeys
 */

class PaymentController extends Controller {
    private $db;
    private $stripeSecretKey = 'sk_test_YOUR_SECRET_KEY'; // CHANGE THIS!
    private $stripePublishableKey = 'pk_test_YOUR_PUBLISHABLE_KEY'; // CHANGE THIS!

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();

        // Initialize Stripe
        if (class_exists('\Stripe\Stripe')) {
            \Stripe\Stripe::setApiKey($this->stripeSecretKey);
        }
    }

    /**
     * Create payment intent for quote
     */
    public function createPaymentIntent() {
        if (!isset($_SESSION['user'])) {
            $this->json(['error' => 'Unauthorized'], 401);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $quoteId = $input['quote_id'] ?? null;
        $amount = $input['amount'] ?? null;

        if (!$quoteId || !$amount) {
            $this->json(['error' => 'Quote ID and amount required'], 400);
            return;
        }

        try {
            // Verify quote belongs to user
            $stmt = $this->db->prepare("
                SELECT q.*, p.client_id, p.title as project_title
                FROM quotes q
                JOIN projects p ON q.project_id = p.id
                WHERE q.id = :quote_id AND p.client_id = :user_id
            ");
            $stmt->execute([
                'quote_id' => $quoteId,
                'user_id' => $_SESSION['user']['id']
            ]);
            $quote = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$quote) {
                $this->json(['error' => 'Quote not found'], 404);
                return;
            }

            // Create Stripe Payment Intent
            if (class_exists('\Stripe\PaymentIntent')) {
                $paymentIntent = \Stripe\PaymentIntent::create([
                    'amount' => (int)($amount * 100), // Amount in cents
                    'currency' => strtolower($input['currency'] ?? 'eur'),
                    'metadata' => [
                        'quote_id' => $quoteId,
                        'project_title' => $quote['project_title'],
                        'user_id' => $_SESSION['user']['id']
                    ],
                    'description' => 'Payment for quote #' . $quoteId
                ]);

                // Save payment intent to database
                $stmt = $this->db->prepare("
                    INSERT INTO payments (quote_id, user_id, amount, currency, stripe_payment_intent_id, status)
                    VALUES (:quote_id, :user_id, :amount, :currency, :intent_id, 'pending')
                ");
                $stmt->execute([
                    'quote_id' => $quoteId,
                    'user_id' => $_SESSION['user']['id'],
                    'amount' => $amount,
                    'currency' => $input['currency'] ?? 'EUR',
                    'intent_id' => $paymentIntent->id
                ]);

                $this->json([
                    'success' => true,
                    'client_secret' => $paymentIntent->client_secret,
                    'payment_intent_id' => $paymentIntent->id
                ]);
            } else {
                $this->json(['error' => 'Stripe library not installed'], 500);
            }
        } catch (\Exception $e) {
            $this->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Webhook endpoint for Stripe events
     */
    public function webhook() {
        $payload = @file_get_contents('php://input');
        $sigHeader = $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '';
        $webhookSecret = 'whsec_YOUR_WEBHOOK_SECRET'; // CHANGE THIS!

        try {
            if (class_exists('\Stripe\Webhook')) {
                $event = \Stripe\Webhook::constructEvent(
                    $payload,
                    $sigHeader,
                    $webhookSecret
                );

                // Handle different event types
                switch ($event->type) {
                    case 'payment_intent.succeeded':
                        $this->handlePaymentSucceeded($event->data->object);
                        break;

                    case 'payment_intent.payment_failed':
                        $this->handlePaymentFailed($event->data->object);
                        break;

                    case 'charge.refunded':
                        $this->handleRefund($event->data->object);
                        break;

                    default:
                        error_log('Unhandled event type: ' . $event->type);
                }

                http_response_code(200);
                echo json_encode(['success' => true]);
            }
        } catch (\Exception $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    /**
     * Handle successful payment
     */
    private function handlePaymentSucceeded($paymentIntent) {
        $intentId = $paymentIntent->id;
        $quoteId = $paymentIntent->metadata->quote_id ?? null;

        if (!$quoteId) {
            return;
        }

        try {
            $this->db->beginTransaction();

            // Update payment status
            $stmt = $this->db->prepare("
                UPDATE payments
                SET status = 'completed', completed_at = NOW()
                WHERE stripe_payment_intent_id = :intent_id
            ");
            $stmt->execute(['intent_id' => $intentId]);

            // Update quote status to paid
            $stmt = $this->db->prepare("
                UPDATE quotes
                SET payment_status = 'paid', updated_at = NOW()
                WHERE id = :quote_id
            ");
            $stmt->execute(['quote_id' => $quoteId]);

            // Create notification for artisan
            $stmt = $this->db->prepare("
                INSERT INTO notifications (user_id, type, title, message, related_id)
                SELECT artisan_id, 'payment_received', 'Paiement reçu',
                       CONCAT('Paiement reçu pour le devis #', :quote_id),
                       :quote_id
                FROM quotes WHERE id = :quote_id
            ");
            $stmt->execute(['quote_id' => $quoteId]);

            $this->db->commit();
        } catch (\Exception $e) {
            $this->db->rollBack();
            error_log('Error handling payment success: ' . $e->getMessage());
        }
    }

    /**
     * Handle failed payment
     */
    private function handlePaymentFailed($paymentIntent) {
        $intentId = $paymentIntent->id;

        try {
            $stmt = $this->db->prepare("
                UPDATE payments
                SET status = 'failed', failure_reason = :reason
                WHERE stripe_payment_intent_id = :intent_id
            ");
            $stmt->execute([
                'intent_id' => $intentId,
                'reason' => $paymentIntent->last_payment_error->message ?? 'Unknown'
            ]);
        } catch (\Exception $e) {
            error_log('Error handling payment failure: ' . $e->getMessage());
        }
    }

    /**
     * Handle refund
     */
    private function handleRefund($charge) {
        // Handle refund logic
        try {
            $stmt = $this->db->prepare("
                UPDATE payments
                SET status = 'refunded', refunded_at = NOW()
                WHERE stripe_payment_intent_id = :intent_id
            ");
            $stmt->execute(['intent_id' => $charge->payment_intent]);
        } catch (\Exception $e) {
            error_log('Error handling refund: ' . $e->getMessage());
        }
    }

    /**
     * Get payment history for user
     */
    public function getPaymentHistory() {
        if (!isset($_SESSION['user'])) {
            $this->json(['error' => 'Unauthorized'], 401);
            return;
        }

        try {
            $stmt = $this->db->prepare("
                SELECT p.*, q.amount as quote_amount, pr.title as project_title,
                       u.first_name, u.last_name
                FROM payments p
                JOIN quotes q ON p.quote_id = q.id
                JOIN projects pr ON q.project_id = pr.id
                JOIN users u ON q.artisan_id = u.id
                WHERE p.user_id = :user_id
                ORDER BY p.created_at DESC
            ");
            $stmt->execute(['user_id' => $_SESSION['user']['id']]);
            $payments = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $this->json([
                'success' => true,
                'payments' => $payments
            ]);
        } catch (\Exception $e) {
            $this->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Create subscription for premium features
     */
    public function createSubscription() {
        if (!isset($_SESSION['user'])) {
            $this->json(['error' => 'Unauthorized'], 401);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $priceId = $input['price_id'] ?? null; // Stripe Price ID

        if (!$priceId) {
            $this->json(['error' => 'Price ID required'], 400);
            return;
        }

        try {
            // Get or create Stripe customer
            $customerId = $this->getOrCreateStripeCustomer($_SESSION['user']['id']);

            // Create subscription
            if (class_exists('\Stripe\Subscription')) {
                $subscription = \Stripe\Subscription::create([
                    'customer' => $customerId,
                    'items' => [['price' => $priceId]],
                    'payment_behavior' => 'default_incomplete',
                    'payment_settings' => [
                        'save_default_payment_method' => 'on_subscription'
                    ],
                    'expand' => ['latest_invoice.payment_intent']
                ]);

                // Save subscription to database
                $stmt = $this->db->prepare("
                    INSERT INTO subscriptions (user_id, stripe_subscription_id, stripe_customer_id, status, plan_id)
                    VALUES (:user_id, :subscription_id, :customer_id, 'active', :price_id)
                ");
                $stmt->execute([
                    'user_id' => $_SESSION['user']['id'],
                    'subscription_id' => $subscription->id,
                    'customer_id' => $customerId,
                    'price_id' => $priceId
                ]);

                $this->json([
                    'success' => true,
                    'subscription_id' => $subscription->id,
                    'client_secret' => $subscription->latest_invoice->payment_intent->client_secret
                ]);
            }
        } catch (\Exception $e) {
            $this->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Get or create Stripe customer
     */
    private function getOrCreateStripeCustomer($userId) {
        // Check if customer already exists
        $stmt = $this->db->prepare("
            SELECT stripe_customer_id FROM users WHERE id = :user_id
        ");
        $stmt->execute(['user_id' => $userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user['stripe_customer_id']) {
            return $user['stripe_customer_id'];
        }

        // Create new Stripe customer
        $stmt = $this->db->prepare("
            SELECT email, first_name, last_name FROM users WHERE id = :user_id
        ");
        $stmt->execute(['user_id' => $userId]);
        $userInfo = $stmt->fetch(PDO::FETCH_ASSOC);

        if (class_exists('\Stripe\Customer')) {
            $customer = \Stripe\Customer::create([
                'email' => $userInfo['email'],
                'name' => $userInfo['first_name'] . ' ' . $userInfo['last_name'],
                'metadata' => ['user_id' => $userId]
            ]);

            // Update user with customer ID
            $stmt = $this->db->prepare("
                UPDATE users SET stripe_customer_id = :customer_id WHERE id = :user_id
            ");
            $stmt->execute([
                'customer_id' => $customer->id,
                'user_id' => $userId
            ]);

            return $customer->id;
        }

        return null;
    }

    /**
     * Get publishable key for mobile app
     */
    public function getPublishableKey() {
        $this->json([
            'success' => true,
            'publishable_key' => $this->stripePublishableKey
        ]);
    }
}
