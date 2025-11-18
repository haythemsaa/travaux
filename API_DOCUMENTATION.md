# Travaux Pro - API Documentation 📚

Complete REST API documentation for Travaux Pro platform.

## Base URL

```
Production: https://your-domain.com/api
Development: http://localhost/api
```

## Authentication

All protected endpoints require JWT authentication.

### Headers

```
Authorization: Bearer {your-jwt-token}
Content-Type: application/json
```

### Get Token

**Login:**
```http
POST /api/auth/login
```

**Request:**
```json
{
  "email": "user@example.com",
  "password": "password123"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Login successful",
  "data": {
    "user": {
      "id": 1,
      "email": "user@example.com",
      "first_name": "John",
      "last_name": "Doe",
      "role": "client"
    },
    "token": "eyJ0eXAiOiJKV1QiLCJhbGc..."
  }
}
```

---

## Authentication Endpoints

### POST /api/auth/register

Register new user.

**Request:**
```json
{
  "email": "user@example.com",
  "password": "password123",
  "first_name": "John",
  "last_name": "Doe",
  "phone": "+33612345678",
  "role": "client",
  "country_code": "FR"
}
```

**Response:** Same as login

---

### POST /api/auth/refresh

Refresh JWT token.

**Response:**
```json
{
  "success": true,
  "data": {
    "token": "new-jwt-token"
  }
}
```

---

### GET /api/auth/me

Get current user info.

**Response:**
```json
{
  "success": true,
  "data": {
    "user": {
      "id": 1,
      "email": "user@example.com",
      "first_name": "John",
      "last_name": "Doe",
      "role": "client",
      "country_code": "FR",
      "created_at": "2024-01-15 10:30:00"
    }
  }
}
```

---

## Projects Endpoints

### GET /api/projects

Get list of projects.

**Query Parameters:**
- `status` - Filter by status (open, in_progress, completed, cancelled)
- `category_id` - Filter by category
- `page` - Page number
- `limit` - Items per page

**Response:**
```json
{
  "success": true,
  "data": {
    "projects": [
      {
        "id": 1,
        "title": "Renovation cuisine",
        "description": "Complete kitchen renovation",
        "category_id": 5,
        "category_name": "Kitchen",
        "budget_min": 5000,
        "budget_max": 10000,
        "currency_code": "EUR",
        "address": "123 Rue Example",
        "postal_code": "75001",
        "city": "Paris",
        "status": "open",
        "urgency": "normal",
        "quotes_count": 3,
        "created_at": "2024-01-15 10:30:00"
      }
    ]
  }
}
```

---

### GET /api/projects/{id}

Get project details.

**Response:**
```json
{
  "success": true,
  "data": {
    "project": {
      "id": 1,
      "title": "Renovation cuisine",
      "description": "Complete kitchen renovation...",
      "category_id": 5,
      "budget_min": 5000,
      "budget_max": 10000,
      "currency_code": "EUR",
      "address": "123 Rue Example",
      "postal_code": "75001",
      "city": "Paris",
      "country_code": "FR",
      "latitude": 48.8566,
      "longitude": 2.3522,
      "status": "open",
      "urgency": "normal",
      "preferred_date": "2024-02-01",
      "created_at": "2024-01-15 10:30:00",
      "custom_fields": [
        {
          "field_id": 1,
          "label": "Kitchen size",
          "field_value": "15m²"
        }
      ],
      "quotes": [
        {
          "id": 1,
          "artisan_id": 5,
          "artisan_name": "John Smith",
          "amount": 7500,
          "status": "pending"
        }
      ]
    }
  }
}
```

---

### POST /api/projects

Create new project.

**Request:**
```json
{
  "title": "Renovation cuisine",
  "description": "Complete kitchen renovation needed",
  "category_id": 5,
  "budget_min": 5000,
  "budget_max": 10000,
  "currency_code": "EUR",
  "address": "123 Rue Example",
  "postal_code": "75001",
  "city": "Paris",
  "country_code": "FR",
  "preferred_date": "2024-02-01",
  "urgency": "normal",
  "latitude": 48.8566,
  "longitude": 2.3522,
  "custom_fields": {
    "1": "15m²",
    "2": "En L"
  }
}
```

**Response:**
```json
{
  "success": true,
  "message": "Project created successfully",
  "data": {
    "project_id": 1
  }
}
```

---

### PUT /api/projects/{id}

Update project.

**Request:** Same fields as POST (all optional)

---

### DELETE /api/projects/{id}

Delete project.

**Response:**
```json
{
  "success": true,
  "message": "Project deleted successfully"
}
```

---

## Quotes Endpoints

### GET /api/quotes

Get list of quotes (sent or received based on user role).

**Response:**
```json
{
  "success": true,
  "data": {
    "quotes": [
      {
        "id": 1,
        "project_id": 1,
        "project_title": "Renovation cuisine",
        "artisan_id": 5,
        "artisan_name": "John Smith",
        "amount": 7500,
        "currency_code": "EUR",
        "description": "Complete renovation with...",
        "estimated_duration": "2 weeks",
        "start_date": "2024-02-01",
        "status": "pending",
        "valid_until": "2024-01-31",
        "created_at": "2024-01-16 14:20:00"
      }
    ]
  }
}
```

---

### POST /api/quotes

Create new quote (artisan only).

**Request:**
```json
{
  "project_id": 1,
  "amount": 7500,
  "currency_code": "EUR",
  "description": "Complete kitchen renovation including...",
  "estimated_duration": "2 weeks",
  "start_date": "2024-02-01",
  "payment_terms": "50% upfront, 50% on completion",
  "valid_until": "2024-01-31"
}
```

---

### PUT /api/quotes/{id}/accept

Accept quote (client only).

---

### PUT /api/quotes/{id}/reject

Reject quote (client only).

---

## Messages Endpoints

### GET /api/messages

Get conversations list.

**Response:**
```json
{
  "success": true,
  "data": {
    "conversations": [
      {
        "user_id": 5,
        "user_name": "John Smith",
        "last_message": "When can we start?",
        "last_message_at": "2024-01-16 15:30:00",
        "unread_count": 2
      }
    ]
  }
}
```

---

### GET /api/messages/{userId}

Get messages with specific user.

**Response:**
```json
{
  "success": true,
  "data": {
    "messages": [
      {
        "id": 1,
        "sender_id": 1,
        "receiver_id": 5,
        "message": "Hello, interested in your quote",
        "project_id": 1,
        "is_read": true,
        "created_at": "2024-01-16 14:00:00"
      }
    ]
  }
}
```

---

### POST /api/messages

Send message.

**Request:**
```json
{
  "receiver_id": 5,
  "message": "When can you start the project?",
  "project_id": 1
}
```

---

## Artisans Endpoints

### GET /api/artisans

Search artisans.

**Query Parameters:**
- `category_id` - Filter by specialty
- `city` - Filter by city
- `postal_code` - Filter by postal code
- `rating_min` - Minimum rating (1-5)
- `latitude` - User location latitude
- `longitude` - User location longitude
- `radius` - Search radius in km

**Response:**
```json
{
  "success": true,
  "data": {
    "artisans": [
      {
        "id": 5,
        "first_name": "John",
        "last_name": "Smith",
        "company_name": "Smith Renovations",
        "specialties": ["Kitchen", "Bathroom"],
        "experience_years": 15,
        "city": "Paris",
        "rating_average": 4.8,
        "review_count": 45,
        "distance": 5.2
      }
    ]
  }
}
```

---

### GET /api/artisans/{id}

Get artisan profile.

**Response:**
```json
{
  "success": true,
  "data": {
    "artisan": {
      "id": 5,
      "first_name": "John",
      "last_name": "Smith",
      "company_name": "Smith Renovations",
      "description": "Professional renovations...",
      "specialties": ["Kitchen", "Bathroom"],
      "experience_years": 15,
      "city": "Paris",
      "rating_average": 4.8,
      "review_count": 45,
      "badges": [
        {
          "name": "SIRET Vérifié",
          "icon": "check-circle",
          "color": "#10b981"
        }
      ],
      "portfolio": [
        {
          "id": 1,
          "title": "Modern Kitchen",
          "description": "...",
          "image_url": "/uploads/portfolio1.jpg"
        }
      ],
      "recent_reviews": [...]
    }
  }
}
```

---

## Reviews Endpoints

### POST /api/reviews

Create review (client only).

**Request:**
```json
{
  "artisan_id": 5,
  "project_id": 1,
  "rating": 5,
  "quality_work": 5,
  "professionalism": 5,
  "communication": 4,
  "value_for_money": 4,
  "comment": "Excellent work, highly recommended!",
  "recommend": 1
}
```

---

## Notifications Endpoints

### GET /api/notifications

Get user notifications.

**Response:**
```json
{
  "success": true,
  "data": {
    "notifications": [
      {
        "id": 1,
        "type": "new_quote",
        "title": "New quote received",
        "message": "You received a quote for Renovation cuisine",
        "related_id": 1,
        "is_read": false,
        "created_at": "2024-01-16 14:00:00"
      }
    ]
  }
}
```

---

### PUT /api/notifications/{id}/read

Mark notification as read.

---

### PUT /api/notifications/read-all

Mark all notifications as read.

---

## Upload Endpoints

### POST /api/upload

Upload image.

**Request:** multipart/form-data
- `image` - Image file (max 5MB)

**Response:**
```json
{
  "success": true,
  "message": "Image uploaded successfully",
  "data": {
    "filename": "abc123.jpg",
    "url": "/uploads/abc123.jpg"
  }
}
```

---

## Statistics Endpoints

### GET /api/stats/dashboard

Get dashboard statistics.

**Response (Client):**
```json
{
  "success": true,
  "data": {
    "stats": {
      "total_projects": 5,
      "active_projects": 2,
      "total_quotes": 12,
      "recent_projects": [...]
    }
  }
}
```

**Response (Artisan):**
```json
{
  "success": true,
  "data": {
    "stats": {
      "projects_viewed": 45,
      "quotes_sent": 32,
      "quotes_accepted": 18,
      "conversion_rate": 56.25,
      "profile_views": 234,
      "recent_quotes": [...]
    }
  }
}
```

---

## Payment Endpoints (Stripe)

### POST /api/payment/create-intent

Create payment intent for quote.

**Request:**
```json
{
  "quote_id": 1,
  "amount": 7500,
  "currency": "EUR"
}
```

**Response:**
```json
{
  "success": true,
  "client_secret": "pi_xxx_secret_xxx",
  "payment_intent_id": "pi_xxx"
}
```

---

### GET /api/payment/publishable-key

Get Stripe publishable key for mobile app.

---

### POST /api/payment/webhook

Stripe webhook endpoint (handles payment events).

---

## Geolocation Endpoints

### GET /api/geolocation/artisans-nearby

Find artisans near location.

**Query Parameters:**
- `latitude` - Location latitude
- `longitude` - Location longitude
- `radius` - Search radius in km (default: 50)
- `category_id` - Filter by specialty

---

### GET /api/geolocation/projects-nearby

Find projects near artisan location.

---

## Error Responses

All endpoints return errors in this format:

```json
{
  "success": false,
  "error": "Error message here"
}
```

**HTTP Status Codes:**
- `200` - Success
- `201` - Created
- `400` - Bad Request
- `401` - Unauthorized
- `403` - Forbidden
- `404` - Not Found
- `500` - Server Error

---

## Rate Limiting

API requests are limited to:
- **Free:** 100 requests/hour
- **Basic:** 500 requests/hour
- **Premium:** 2000 requests/hour
- **Enterprise:** Unlimited

---

## Webhooks

Travaux Pro can send webhooks for events:

**Events:**
- `quote.created`
- `quote.accepted`
- `quote.rejected`
- `payment.succeeded`
- `payment.failed`
- `project.created`
- `message.received`

**Webhook Payload:**
```json
{
  "event": "quote.created",
  "data": {...},
  "timestamp": "2024-01-16T14:00:00Z"
}
```

---

## SDK & Libraries

**JavaScript/TypeScript:**
```bash
npm install @travaux-pro/sdk
```

**PHP:**
```bash
composer require travaux-pro/php-sdk
```

**React Native:**
Already included in mobile app (`src/services/api.js`)

---

## Support

- **Documentation:** https://docs.travauxpro.com
- **Email:** api@travauxpro.com
- **Status:** https://status.travauxpro.com

---

**Version:** 1.0.0
**Last Updated:** 2024
