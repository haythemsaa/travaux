-- =====================================================
-- PAYMENT SYSTEM - Stripe Integration
-- Tables for handling payments, subscriptions, and transactions
-- =====================================================

-- Payments table
CREATE TABLE IF NOT EXISTS payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    quote_id INT NOT NULL,
    user_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    currency VARCHAR(3) DEFAULT 'EUR',
    stripe_payment_intent_id VARCHAR(255) UNIQUE,
    stripe_charge_id VARCHAR(255),
    status ENUM('pending', 'processing', 'completed', 'failed', 'refunded', 'cancelled') DEFAULT 'pending',
    payment_method VARCHAR(50) COMMENT 'card, bank_transfer, etc',
    failure_reason TEXT,
    refund_amount DECIMAL(10,2),
    refund_reason TEXT,
    metadata JSON COMMENT 'Additional payment data',
    completed_at TIMESTAMP NULL,
    refunded_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (quote_id) REFERENCES quotes(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user (user_id),
    INDEX idx_quote (quote_id),
    INDEX idx_status (status),
    INDEX idx_stripe_intent (stripe_payment_intent_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Subscriptions table (for premium features)
CREATE TABLE IF NOT EXISTS subscriptions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    plan_id VARCHAR(50) NOT NULL COMMENT 'free, basic, premium, enterprise',
    stripe_subscription_id VARCHAR(255) UNIQUE,
    stripe_customer_id VARCHAR(255),
    status ENUM('active', 'past_due', 'cancelled', 'incomplete', 'trialing') DEFAULT 'active',
    current_period_start TIMESTAMP NULL,
    current_period_end TIMESTAMP NULL,
    cancel_at_period_end BOOLEAN DEFAULT FALSE,
    cancelled_at TIMESTAMP NULL,
    trial_end TIMESTAMP NULL,
    metadata JSON COMMENT 'Additional subscription data',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user (user_id),
    INDEX idx_status (status),
    INDEX idx_stripe_sub (stripe_subscription_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Subscription plans table
CREATE TABLE IF NOT EXISTS subscription_plans (
    id INT AUTO_INCREMENT PRIMARY KEY,
    plan_id VARCHAR(50) UNIQUE NOT NULL,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price_monthly DECIMAL(10,2) NOT NULL,
    price_yearly DECIMAL(10,2),
    currency VARCHAR(3) DEFAULT 'EUR',
    stripe_price_id_monthly VARCHAR(255),
    stripe_price_id_yearly VARCHAR(255),
    features JSON COMMENT 'List of features included',
    limits JSON COMMENT 'Usage limits (leads_per_month, etc)',
    is_active BOOLEAN DEFAULT TRUE,
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_plan_id (plan_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Payment transactions (for detailed tracking)
CREATE TABLE IF NOT EXISTS payment_transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    payment_id INT NOT NULL,
    transaction_type ENUM('charge', 'refund', 'dispute', 'chargeback') NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    currency VARCHAR(3) DEFAULT 'EUR',
    stripe_transaction_id VARCHAR(255),
    status ENUM('pending', 'succeeded', 'failed') DEFAULT 'pending',
    description TEXT,
    metadata JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (payment_id) REFERENCES payments(id) ON DELETE CASCADE,
    INDEX idx_payment (payment_id),
    INDEX idx_type (transaction_type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Wallet/Credits system (optional - for internal credits)
CREATE TABLE IF NOT EXISTS wallets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    balance DECIMAL(10,2) DEFAULT 0.00,
    currency VARCHAR(3) DEFAULT 'EUR',
    pending_balance DECIMAL(10,2) DEFAULT 0.00 COMMENT 'Pending from completed projects',
    total_earned DECIMAL(10,2) DEFAULT 0.00,
    total_withdrawn DECIMAL(10,2) DEFAULT 0.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Wallet transactions
CREATE TABLE IF NOT EXISTS wallet_transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    wallet_id INT NOT NULL,
    type ENUM('credit', 'debit', 'withdrawal', 'refund') NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    balance_after DECIMAL(10,2) NOT NULL,
    description TEXT,
    related_type VARCHAR(50) COMMENT 'quote, payment, withdrawal',
    related_id INT COMMENT 'ID of related entity',
    metadata JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (wallet_id) REFERENCES wallets(id) ON DELETE CASCADE,
    INDEX idx_wallet (wallet_id),
    INDEX idx_type (type),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Payout requests (for artisans)
CREATE TABLE IF NOT EXISTS payout_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    currency VARCHAR(3) DEFAULT 'EUR',
    status ENUM('pending', 'processing', 'completed', 'rejected') DEFAULT 'pending',
    payout_method VARCHAR(50) COMMENT 'bank_transfer, paypal, etc',
    bank_account_info JSON COMMENT 'Encrypted bank details',
    stripe_payout_id VARCHAR(255),
    processing_fee DECIMAL(10,2) DEFAULT 0.00,
    net_amount DECIMAL(10,2) NOT NULL,
    requested_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    processed_at TIMESTAMP NULL,
    completed_at TIMESTAMP NULL,
    rejection_reason TEXT,
    admin_notes TEXT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user (user_id),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Add stripe_customer_id to users table
ALTER TABLE users ADD COLUMN IF NOT EXISTS stripe_customer_id VARCHAR(255) UNIQUE AFTER country_code;

-- Add payment_status to quotes table
ALTER TABLE quotes ADD COLUMN IF NOT EXISTS payment_status ENUM('unpaid', 'pending', 'paid', 'refunded') DEFAULT 'unpaid' AFTER status;

-- =====================================================
-- INSERT DEFAULT SUBSCRIPTION PLANS
-- =====================================================

INSERT INTO subscription_plans (plan_id, name, description, price_monthly, price_yearly, features, limits) VALUES
('free', 'Gratuit', 'Plan de base pour commencer', 0.00, 0.00,
 '["3 devis par mois", "Profil de base", "Recherche standard"]',
 '{"leads_per_month": 3, "quotes_per_month": 3}'),

('basic', 'Basic', 'Pour artisans actifs', 29.99, 299.99,
 '["15 devis par mois", "Profil détaillé", "Badge vérifié", "Analytics de base", "Support email"]',
 '{"leads_per_month": 15, "quotes_per_month": 15}'),

('premium', 'Premium', 'Pour professionnels établis', 79.99, 799.99,
 '["50 devis par mois", "Profil premium", "Tous les badges", "Analytics avancées", "Comparateur de prix", "Support prioritaire", "Templates de devis"]',
 '{"leads_per_month": 50, "quotes_per_month": 50}'),

('enterprise', 'Enterprise', 'Pour grandes entreprises', 199.99, 1999.99,
 '["Devis illimités", "Profil entreprise", "API access", "Analytics complètes", "Manager de compte dédié", "Intégration personnalisée", "Support 24/7"]',
 '{"leads_per_month": -1, "quotes_per_month": -1}');

-- =====================================================
-- EXAMPLE QUERIES
-- =====================================================

-- Get user's payment history
-- SELECT p.*, q.amount, pr.title
-- FROM payments p
-- JOIN quotes q ON p.quote_id = q.id
-- JOIN projects pr ON q.project_id = pr.id
-- WHERE p.user_id = 1
-- ORDER BY p.created_at DESC;

-- Get active subscriptions
-- SELECT s.*, sp.name, sp.price_monthly, u.email
-- FROM subscriptions s
-- JOIN subscription_plans sp ON s.plan_id = sp.plan_id
-- JOIN users u ON s.user_id = u.id
-- WHERE s.status = 'active';

-- Get wallet balance
-- SELECT w.*, u.email, u.first_name, u.last_name
-- FROM wallets w
-- JOIN users u ON w.user_id = u.id
-- WHERE w.user_id = 1;
