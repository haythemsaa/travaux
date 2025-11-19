## API Examples - Travaux Pro

Guide pratique avec des exemples concrets d'utilisation de l'API REST.

## 📝 Table des matières

1. [Authentication](#authentication)
2. [Projects](#projects)
3. [Quotes](#quotes)
4. [Messages](#messages)
5. [Artisans](#artisans)
6. [Trades](#trades)
7. [Reviews](#reviews)
8. [Notifications](#notifications)
9. [Analytics](#analytics)
10. [Favorites](#favorites)

---

## Authentication

### Register (Client)

```bash
curl -X POST http://localhost:8000/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "email": "nouveau.client@example.com",
    "password": "password123",
    "role": "client",
    "first_name": "Jean",
    "last_name": "Dupont",
    "phone": "+33612345678",
    "address": "15 Rue Example",
    "city": "Paris",
    "postal_code": "75001",
    "country": "FR",
    "language": "fr"
  }'
```

**Response:**
```json
{
  "success": true,
  "message": "Utilisateur créé avec succès",
  "data": {
    "user": {
      "id": 10,
      "email": "nouveau.client@example.com",
      "role": "client",
      "first_name": "Jean",
      "last_name": "Dupont"
    },
    "token": "eyJ0eXAiOiJKV1QiLCJhbGc..."
  }
}
```

### Register (Artisan)

```bash
curl -X POST http://localhost:8000/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "email": "artisan@example.com",
    "password": "password123",
    "role": "artisan",
    "first_name": "Pierre",
    "last_name": "Martin",
    "phone": "+33698765432",
    "company_name": "Électricité Martin",
    "siret": "12345678900012",
    "trade_category_id": 15,
    "experience_years": 10,
    "service_radius": 50,
    "hourly_rate": 45.00
  }'
```

### Login

```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "client@demo.com",
    "password": "password123"
  }'
```

**Response:**
```json
{
  "success": true,
  "data": {
    "user": {
      "id": 1,
      "email": "client@demo.com",
      "role": "client",
      "first_name": "Jean",
      "last_name": "Dupont"
    },
    "token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
    "expires_at": "2024-11-20T12:00:00Z"
  }
}
```

### Get Current User

```bash
curl http://localhost:8000/api/auth/me \
  -H "Authorization: Bearer YOUR_TOKEN"
```

---

## Projects

### Create Project

```bash
curl -X POST http://localhost:8000/api/projects \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "trade_category_id": 15,
    "title": "Rénovation électrique appartement",
    "description": "Rénovation complète installation électrique, mise aux normes",
    "budget": 3500.00,
    "urgency": "medium",
    "address": "15 Rue Example",
    "city": "Paris",
    "postal_code": "75001",
    "latitude": 48.8566,
    "longitude": 2.3522,
    "custom_fields": {
      "work_type": "Installation complète",
      "property_size": "70",
      "num_rooms": "3"
    }
  }'
```

### Get Projects List

```bash
# Tous les projets
curl http://localhost:8000/api/projects \
  -H "Authorization: Bearer YOUR_TOKEN"

# Avec filtres
curl "http://localhost:8000/api/projects?status=open&category=15&urgency=high" \
  -H "Authorization: Bearer YOUR_TOKEN"

# Avec pagination
curl "http://localhost:8000/api/projects?page=1&limit=10" \
  -H "Authorization: Bearer YOUR_TOKEN"
```

### Get Project Details

```bash
curl http://localhost:8000/api/projects/1 \
  -H "Authorization: Bearer YOUR_TOKEN"
```

**Response:**
```json
{
  "success": true,
  "data": {
    "project": {
      "id": 1,
      "title": "Rénovation électrique appartement",
      "description": "Rénovation complète...",
      "budget": 3500.00,
      "status": "open",
      "urgency": "medium",
      "created_at": "2024-11-15T10:30:00Z",
      "user": {
        "id": 1,
        "first_name": "Jean",
        "last_name": "Dupont"
      },
      "category": {
        "id": 15,
        "name_fr": "Électricité"
      },
      "quotes_count": 2,
      "custom_fields": {
        "work_type": "Installation complète",
        "property_size": "70"
      }
    }
  }
}
```

### Update Project

```bash
curl -X PUT http://localhost:8000/api/projects/1 \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Rénovation électrique - URGENT",
    "urgency": "urgent",
    "budget": 4000.00
  }'
```

### Delete Project

```bash
curl -X DELETE http://localhost:8000/api/projects/1 \
  -H "Authorization: Bearer YOUR_TOKEN"
```

---

## Quotes

### Create Quote (Artisan)

```bash
curl -X POST http://localhost:8000/api/quotes \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "project_id": 1,
    "amount": 3200.00,
    "duration": "5 jours",
    "description": "Rénovation électrique complète:\n- Tableau électrique\n- 15 prises\n- Mise aux normes",
    "valid_until": "2024-12-20"
  }'
```

### Get Quotes

```bash
# Pour un projet spécifique
curl http://localhost:8000/api/projects/1/quotes \
  -H "Authorization: Bearer YOUR_TOKEN"

# Tous mes devis (artisan)
curl http://localhost:8000/api/quotes \
  -H "Authorization: Bearer YOUR_TOKEN"

# Avec filtres
curl "http://localhost:8000/api/quotes?status=pending&project_id=1" \
  -H "Authorization: Bearer YOUR_TOKEN"
```

### Accept Quote (Client)

```bash
curl -X PUT http://localhost:8000/api/quotes/1/accept \
  -H "Authorization: Bearer YOUR_TOKEN"
```

### Reject Quote (Client)

```bash
curl -X PUT http://localhost:8000/api/quotes/1/reject \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "reason": "Budget trop élevé"
  }'
```

---

## Messages

### Send Message

```bash
curl -X POST http://localhost:8000/api/messages \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "recipient_id": 4,
    "message": "Bonjour, je suis intéressé par votre devis. Pouvez-vous me préciser le délai ?"
  }'
```

### Get Conversations

```bash
curl http://localhost:8000/api/messages \
  -H "Authorization: Bearer YOUR_TOKEN"
```

**Response:**
```json
{
  "success": true,
  "data": {
    "conversations": [
      {
        "user_id": 4,
        "user_name": "Pierre Bernard",
        "company_name": "Électricité Bernard",
        "last_message": "Je peux intervenir dès la semaine prochaine",
        "last_message_at": "2024-11-19T14:30:00Z",
        "unread_count": 2
      }
    ]
  }
}
```

### Get Messages with User

```bash
curl http://localhost:8000/api/messages/4 \
  -H "Authorization: Bearer YOUR_TOKEN"
```

---

## Artisans

### Search Artisans

```bash
# Recherche basique
curl http://localhost:8000/api/artisans

# Avec filtres
curl "http://localhost:8000/api/artisans?category=15&city=Paris&radius=30&min_rating=4.5" \
  -H "Authorization: Bearer YOUR_TOKEN"

# Avec géolocalisation
curl "http://localhost:8000/api/artisans?lat=48.8566&lng=2.3522&radius=50" \
  -H "Authorization: Bearer YOUR_TOKEN"
```

**Response:**
```json
{
  "success": true,
  "data": {
    "artisans": [
      {
        "id": 4,
        "company_name": "Électricité Bernard",
        "first_name": "Pierre",
        "last_name": "Bernard",
        "rating": 4.8,
        "reviews_count": 24,
        "experience_years": 10,
        "hourly_rate": 45.00,
        "city": "Paris",
        "distance": 5.2,
        "badges": ["SIRET Vérifié", "Assurance Décennale"],
        "profile_image": "uploads/profiles/4.jpg"
      }
    ],
    "total": 15,
    "page": 1,
    "limit": 10
  }
}
```

### Get Artisan Profile

```bash
curl http://localhost:8000/api/artisans/4
```

**Response:**
```json
{
  "success": true,
  "data": {
    "artisan": {
      "id": 4,
      "company_name": "Électricité Bernard",
      "description": "Électricien professionnel avec 10 ans d'expérience...",
      "rating": 4.8,
      "reviews_count": 24,
      "experience_years": 10,
      "hourly_rate": 45.00,
      "service_radius": 50,
      "availability": "available",
      "verified": true,
      "badges": [
        {"name": "SIRET Vérifié", "icon": "fa-check-circle"},
        {"name": "Assurance Décennale", "icon": "fa-shield-alt"}
      ],
      "portfolio": [
        {"image": "uploads/portfolio/1.jpg", "description": "Rénovation appartement"}
      ],
      "reviews": [
        {
          "rating": 5,
          "comment": "Excellent travail !",
          "reviewer_name": "Jean D.",
          "created_at": "2024-11-15T10:00:00Z"
        }
      ]
    }
  }
}
```

---

## Trades

### Get All Trades

```bash
curl http://localhost:8000/api/trades
```

**Response:**
```json
{
  "success": true,
  "data": {
    "trades": [
      {
        "id": 15,
        "slug": "electricite",
        "name_fr": "Électricité",
        "name_en": "Electrical",
        "icon": "fa-bolt",
        "custom_fields_count": 5
      }
    ],
    "total": 60
  }
}
```

### Get Trade Details

```bash
curl http://localhost:8000/api/trades/15
```

### Get Trade Custom Fields

```bash
curl http://localhost:8000/api/trades/15/fields
```

**Response:**
```json
{
  "success": true,
  "data": {
    "fields": [
      {
        "id": 1,
        "field_name": "work_type",
        "field_type": "select",
        "label_fr": "Type de travaux",
        "label_en": "Type of work",
        "options": {
          "options": [
            "Installation complète",
            "Mise aux normes",
            "Réparation"
          ]
        },
        "validation_rules": {
          "required": true
        },
        "is_required": true,
        "display_order": 1
      }
    ]
  }
}
```

---

## Reviews

### Create Review

```bash
curl -X POST http://localhost:8000/api/reviews \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "project_id": 1,
    "reviewed_user_id": 4,
    "rating": 5,
    "comment": "Excellent travail ! Très professionnel et ponctuel. Je recommande vivement."
  }'
```

### Get Artisan Reviews

```bash
curl http://localhost:8000/api/artisans/4/reviews
```

---

## Notifications

### Get Notifications

```bash
curl http://localhost:8000/api/notifications \
  -H "Authorization: Bearer YOUR_TOKEN"
```

### Mark as Read

```bash
curl -X PUT http://localhost:8000/api/notifications/1/read \
  -H "Authorization: Bearer YOUR_TOKEN"
```

### Mark All as Read

```bash
curl -X PUT http://localhost:8000/api/notifications/read-all \
  -H "Authorization: Bearer YOUR_TOKEN"
```

---

## Analytics

### Get Market Prices

```bash
# Tous les prix
curl http://localhost:8000/api/analytics/market-prices

# Par catégorie
curl "http://localhost:8000/api/analytics/market-prices?category=15"

# Par région
curl "http://localhost:8000/api/analytics/market-prices?region=ile-de-france"
```

**Response:**
```json
{
  "success": true,
  "data": {
    "prices": [
      {
        "category": "Électricité",
        "region": "Île-de-France",
        "min_price": 2000.00,
        "avg_price": 3500.00,
        "max_price": 6000.00,
        "currency": "EUR",
        "unit": "projet",
        "sample_size": 45
      }
    ]
  }
}
```

### Compare Project

```bash
curl http://localhost:8000/api/analytics/compare-project/1 \
  -H "Authorization: Bearer YOUR_TOKEN"
```

---

## Favorites

### Get Favorites

```bash
curl http://localhost:8000/api/favorites \
  -H "Authorization: Bearer YOUR_TOKEN"
```

### Toggle Favorite

```bash
curl -X POST http://localhost:8000/api/favorites/toggle/4 \
  -H "Authorization: Bearer YOUR_TOKEN"
```

**Response:**
```json
{
  "success": true,
  "data": {
    "action": "added",
    "message": "Artisan ajouté aux favoris"
  }
}
```

---

## Upload Files

### Upload Project Image

```bash
curl -X POST http://localhost:8000/api/upload \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -F "image=@/path/to/image.jpg" \
  -F "type=project"
```

**Response:**
```json
{
  "success": true,
  "data": {
    "url": "uploads/projects/1234567890.jpg",
    "filename": "1234567890.jpg"
  }
}
```

---

## Error Responses

### 400 Bad Request

```json
{
  "success": false,
  "error": "Données invalides",
  "details": {
    "email": ["Le champ email est requis"],
    "password": ["Le mot de passe doit contenir au moins 8 caractères"]
  }
}
```

### 401 Unauthorized

```json
{
  "success": false,
  "error": "Non authentifié"
}
```

### 403 Forbidden

```json
{
  "success": false,
  "error": "Vous n'avez pas les permissions nécessaires"
}
```

### 404 Not Found

```json
{
  "success": false,
  "error": "Ressource non trouvée"
}
```

### 500 Server Error

```json
{
  "success": false,
  "error": "Erreur serveur interne"
}
```

---

## Rate Limiting

L'API applique des limites de taux :

- **Développement** : 100 requêtes/heure
- **Production** : 1000 requêtes/heure

Headers de réponse :
```
X-RateLimit-Limit: 100
X-RateLimit-Remaining: 95
X-RateLimit-Reset: 1637234567
```

---

## Testing with Postman

1. Importer la collection : `postman/travaux-pro-api-tests.json`
2. Créer un environnement :
   - `base_url` : `http://localhost:8000`
   - `token` : (sera rempli automatiquement après login)
3. Exécuter les tests

---

## JavaScript/TypeScript Examples

### Using Fetch

```javascript
// Login
async function login(email, password) {
  const response = await fetch('http://localhost:8000/api/auth/login', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({ email, password }),
  });

  const data = await response.json();
  if (data.success) {
    localStorage.setItem('token', data.data.token);
    return data.data.user;
  }
  throw new Error(data.error);
}

// Get Projects
async function getProjects() {
  const token = localStorage.getItem('token');
  const response = await fetch('http://localhost:8000/api/projects', {
    headers: {
      'Authorization': `Bearer ${token}`,
    },
  });

  return await response.json();
}
```

### Using Axios

```javascript
import axios from 'axios';

const api = axios.create({
  baseURL: 'http://localhost:8000/api',
});

// Interceptor pour le token
api.interceptors.request.use((config) => {
  const token = localStorage.getItem('token');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

// Create project
async function createProject(data) {
  try {
    const response = await api.post('/projects', data);
    return response.data;
  } catch (error) {
    console.error(error.response.data);
    throw error;
  }
}
```

---

**📚 Pour plus d'informations, consultez `API_DOCUMENTATION.md`**
