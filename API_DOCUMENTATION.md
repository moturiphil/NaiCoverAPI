# NaiCover API Documentation

Complete API documentation for the NaiCover Insurance Platform.

## Base URL

```
Development: http://localhost:8000/api
Production: https://api.naicoverapi.com/api
```

## Authentication

All protected endpoints require Bearer token authentication:

```http
Authorization: Bearer {your_access_token}
```

### Authentication Endpoints

#### Register User

Create a new user account.

**POST** `/register`

**Request Body:**
```json
{
    "name": "John Doe",
    "email": "john@example.com", 
    "password": "password123",
    "password_confirmation": "password123"
}
```

**Response (201):**
```json
{
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "email_verified_at": null,
        "created_at": "2024-01-01T12:00:00.000000Z",
        "updated_at": "2024-01-01T12:00:00.000000Z"
    },
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9...",
    "token_type": "Bearer"
}
```

**Validation Errors (422):**
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "email": ["The email has already been taken."],
        "password": ["The password confirmation does not match."]
    }
}
```

#### Login User

Authenticate user and get access token.

**POST** `/login`

**Request Body:**
```json
{
    "email": "john@example.com",
    "password": "password123"
}
```

**Response (200):**
```json
{
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com"
    },
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9...",
    "token_type": "Bearer"
}
```

**Invalid Credentials (401):**
```json
{
    "message": "Invalid credentials"
}
```

#### Logout User

Revoke user's access token.

**POST** `/logout` 🔒

**Response (200):**
```json
{
    "message": "Successfully logged out"
}
```

#### Get Authenticated User

Get current user's information.

**GET** `/user` 🔒

**Response (200):**
```json
{
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "email_verified_at": null,
    "created_at": "2024-01-01T12:00:00.000000Z",
    "updated_at": "2024-01-01T12:00:00.000000Z",
    "roles": ["customer"]
}
```

---

## User Management

### List Users

Get paginated list of all users.

**GET** `/users`

**Query Parameters:**
- `page` (integer): Page number (default: 1)
- `per_page` (integer): Items per page (default: 15, max: 100)

**Response (200):**
```json
{
    "data": [
        {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com",
            "created_at": "2024-01-01T12:00:00.000000Z"
        }
    ],
    "links": {
        "first": "http://api.example.com/users?page=1",
        "last": "http://api.example.com/users?page=10",
        "prev": null,
        "next": "http://api.example.com/users?page=2"
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 10,
        "per_page": 15,
        "to": 15,
        "total": 150
    }
}
```

### Create User

Create a new user.

**POST** `/users`

**Request Body:**
```json
{
    "name": "Jane Smith",
    "email": "jane@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

**Response (201):**
```json
{
    "id": 2,
    "name": "Jane Smith", 
    "email": "jane@example.com",
    "created_at": "2024-01-01T12:00:00.000000Z",
    "updated_at": "2024-01-01T12:00:00.000000Z"
}
```

### Get User

Get specific user by ID.

**GET** `/users/{id}`

**Response (200):**
```json
{
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "email_verified_at": null,
    "created_at": "2024-01-01T12:00:00.000000Z",
    "updated_at": "2024-01-01T12:00:00.000000Z"
}
```

**Not Found (404):**
```json
{
    "message": "User not found"
}
```

### Update User

Update user information.

**PUT** `/users/{id}`

**Request Body:**
```json
{
    "name": "John Doe Updated",
    "email": "john.updated@example.com"
}
```

**Response (200):**
```json
{
    "id": 1,
    "name": "John Doe Updated",
    "email": "john.updated@example.com", 
    "updated_at": "2024-01-01T12:30:00.000000Z"
}
```

### Delete User

Delete user by ID.

**DELETE** `/users/{id}`

**Response (204):** *No content*

---

## Customer Management 🔒 Admin Only

### List Customers

Get paginated list of customers.

**GET** `/customers` 🔒

**Query Parameters:**
- `page`, `per_page`: Pagination
- `search` (string): Search by name or email
- `status` (string): Filter by status

**Response (200):**
```json
{
    "data": [
        {
            "id": 1,
            "user_id": 5,
            "first_name": "John",
            "last_name": "Doe",
            "email": "john@example.com",
            "phone": "+1234567890",
            "date_of_birth": "1990-01-01",
            "address": "123 Main St",
            "city": "New York",
            "state": "NY",
            "zip_code": "10001",
            "status": "active",
            "created_at": "2024-01-01T12:00:00.000000Z"
        }
    ],
    "meta": {
        "current_page": 1,
        "total": 100
    }
}
```

### Create Customer

Create new customer.

**POST** `/customers` 🔒

**Request Body:**
```json
{
    "user_id": 5,
    "first_name": "John",
    "last_name": "Doe", 
    "email": "john@example.com",
    "phone": "+1234567890",
    "date_of_birth": "1990-01-01",
    "address": "123 Main St",
    "city": "New York",
    "state": "NY",
    "zip_code": "10001"
}
```

**Response (201):**
```json
{
    "id": 1,
    "user_id": 5,
    "first_name": "John",
    "last_name": "Doe",
    "email": "john@example.com",
    "status": "active",
    "created_at": "2024-01-01T12:00:00.000000Z"
}
```

### Get Customer

Get customer by ID.

**GET** `/customers/{id}` 🔒

**Response (200):**
```json
{
    "id": 1,
    "user_id": 5,
    "first_name": "John",
    "last_name": "Doe",
    "email": "john@example.com",
    "phone": "+1234567890",
    "date_of_birth": "1990-01-01",
    "address": "123 Main St",
    "city": "New York", 
    "state": "NY",
    "zip_code": "10001",
    "status": "active",
    "policies": [
        {
            "id": 1,
            "policy_number": "POL123456",
            "status": "active",
            "premium_amount": "500.00"
        }
    ],
    "created_at": "2024-01-01T12:00:00.000000Z"
}
```

### Update Customer

Update customer information.

**PUT** `/customers/{id}` 🔒

**Request Body:**
```json
{
    "phone": "+1234567899",
    "address": "456 Oak St",
    "status": "inactive"
}
```

**Response (200):**
```json
{
    "id": 1,
    "phone": "+1234567899",
    "address": "456 Oak St", 
    "status": "inactive",
    "updated_at": "2024-01-01T12:30:00.000000Z"
}
```

### Delete Customer

Delete customer by ID.

**DELETE** `/customers/{id}` 🔒

**Response (204):** *No content*

---

## Agent Management

### List Agents

Get paginated list of agents.

**GET** `/agents`

**Response (200):**
```json
{
    "data": [
        {
            "id": 1,
            "user_id": 3,
            "agent_code": "AGT001",
            "first_name": "Sarah",
            "last_name": "Wilson",
            "email": "sarah@agency.com",
            "phone": "+1234567890",
            "license_number": "LIC123456",
            "commission_rate": "5.00",
            "status": "active",
            "created_at": "2024-01-01T12:00:00.000000Z"
        }
    ]
}
```

### Create Agent

Create new agent.

**POST** `/agents`

**Request Body:**
```json
{
    "user_id": 3,
    "agent_code": "AGT001",
    "first_name": "Sarah",
    "last_name": "Wilson",
    "email": "sarah@agency.com",
    "phone": "+1234567890",
    "license_number": "LIC123456",
    "commission_rate": 5.00
}
```

**Response (201):**
```json
{
    "id": 1,
    "agent_code": "AGT001",
    "first_name": "Sarah",
    "last_name": "Wilson",
    "status": "active",
    "created_at": "2024-01-01T12:00:00.000000Z"
}
```

### Get Agent

Get agent by ID.

**GET** `/agents/{id}`

**Response (200):**
```json
{
    "id": 1,
    "user_id": 3,
    "agent_code": "AGT001",
    "first_name": "Sarah",
    "last_name": "Wilson",
    "email": "sarah@agency.com",
    "phone": "+1234567890", 
    "license_number": "LIC123456",
    "commission_rate": "5.00",
    "status": "active",
    "policies_count": 25,
    "total_commission": "1250.00",
    "created_at": "2024-01-01T12:00:00.000000Z"
}
```

### Update Agent  

Update agent information.

**PUT** `/agents/{id}`

**Request Body:**
```json
{
    "phone": "+1234567899",
    "commission_rate": 6.00,
    "status": "inactive"
}
```

**Response (200):**
```json
{
    "id": 1,
    "phone": "+1234567899",
    "commission_rate": "6.00",
    "status": "inactive",
    "updated_at": "2024-01-01T12:30:00.000000Z"
}
```

### Delete Agent

Delete agent by ID.

**DELETE** `/agents/{id}`

**Response (204):** *No content*

---

## Insurance Provider Management

### List Insurance Providers

Get list of insurance providers.

**GET** `/insurance_providers`

**Response (200):**
```json
{
    "data": [
        {
            "id": 1,
            "name": "SecureLife Insurance",
            "code": "SLI",
            "email": "contact@securelife.com",
            "phone": "+1234567890",
            "website": "https://securelife.com",
            "address": "789 Insurance Blvd",
            "city": "Chicago",
            "state": "IL", 
            "zip_code": "60601",
            "status": "active",
            "policies_count": 150,
            "created_at": "2024-01-01T12:00:00.000000Z"
        }
    ]
}
```

### Create Insurance Provider

Create new insurance provider.

**POST** `/insurance_providers`

**Request Body:**
```json
{
    "name": "SecureLife Insurance",
    "code": "SLI",
    "email": "contact@securelife.com",
    "phone": "+1234567890",
    "website": "https://securelife.com",
    "address": "789 Insurance Blvd",
    "city": "Chicago",
    "state": "IL",
    "zip_code": "60601"
}
```

**Response (201):**
```json
{
    "id": 1,
    "name": "SecureLife Insurance",
    "code": "SLI", 
    "status": "active",
    "created_at": "2024-01-01T12:00:00.000000Z"
}
```

### Get Insurance Provider

Get insurance provider by ID.

**GET** `/insurance_providers/{id}`

**Response (200):**
```json
{
    "id": 1,
    "name": "SecureLife Insurance",
    "code": "SLI",
    "email": "contact@securelife.com",
    "phone": "+1234567890",
    "website": "https://securelife.com",
    "address": "789 Insurance Blvd",
    "city": "Chicago",
    "state": "IL",
    "zip_code": "60601",
    "status": "active",
    "policies": [
        {
            "id": 1,
            "policy_number": "POL123456",
            "customer_name": "John Doe", 
            "premium_amount": "500.00"
        }
    ],
    "created_at": "2024-01-01T12:00:00.000000Z"
}
```

### Update Insurance Provider

Update insurance provider information.

**PUT** `/insurance_providers/{id}`

**Request Body:**
```json
{
    "phone": "+1234567899",
    "website": "https://newsecurelife.com",
    "status": "inactive"
}
```

**Response (200):**
```json
{
    "id": 1,
    "phone": "+1234567899",
    "website": "https://newsecurelife.com",
    "status": "inactive",
    "updated_at": "2024-01-01T12:30:00.000000Z"
}
```

### Delete Insurance Provider

Delete insurance provider by ID.

**DELETE** `/insurance_providers/{id}`

**Response (204):** *No content*

---

## Policy Management

### List Policies

Get paginated list of policies.

**GET** `/policies`

**Query Parameters:**
- `customer_id` (integer): Filter by customer
- `agent_id` (integer): Filter by agent
- `provider_id` (integer): Filter by provider
- `status` (string): Filter by status (active, expired, cancelled)
- `search` (string): Search by policy number

**Response (200):**
```json
{
    "data": [
        {
            "id": 1,
            "policy_number": "POL123456",
            "customer_id": 1,
            "agent_id": 1,
            "provider_id": 1,
            "policy_type_id": 1,
            "premium_amount": "500.00",
            "start_date": "2024-01-01",
            "end_date": "2024-12-31",
            "status": "active",
            "customer": {
                "id": 1,
                "name": "John Doe"
            },
            "agent": {
                "id": 1,
                "name": "Sarah Wilson"
            },
            "provider": {
                "id": 1,
                "name": "SecureLife Insurance"
            },
            "created_at": "2024-01-01T12:00:00.000000Z"
        }
    ]
}
```

### Create Policy

Create new policy.

**POST** `/policies`

**Request Body:**
```json
{
    "policy_number": "POL123456",
    "customer_id": 1,
    "agent_id": 1,
    "provider_id": 1,
    "policy_type_id": 1,
    "premium_amount": 500.00,
    "start_date": "2024-01-01",
    "end_date": "2024-12-31",
    "coverage_details": {
        "coverage_amount": 100000,
        "deductible": 1000
    }
}
```

**Response (201):**
```json
{
    "id": 1,
    "policy_number": "POL123456",
    "customer_id": 1,
    "premium_amount": "500.00",
    "status": "active",
    "created_at": "2024-01-01T12:00:00.000000Z"
}
```

### Get Policy

Get policy by ID.

**GET** `/policies/{id}`

**Response (200):**
```json
{
    "id": 1,
    "policy_number": "POL123456",
    "customer_id": 1,
    "agent_id": 1,
    "provider_id": 1,
    "policy_type_id": 1,
    "premium_amount": "500.00",
    "start_date": "2024-01-01",
    "end_date": "2024-12-31", 
    "status": "active",
    "coverage_details": {
        "coverage_amount": 100000,
        "deductible": 1000
    },
    "customer": {
        "id": 1,
        "first_name": "John",
        "last_name": "Doe",
        "email": "john@example.com"
    },
    "agent": {
        "id": 1,
        "first_name": "Sarah", 
        "last_name": "Wilson",
        "agent_code": "AGT001"
    },
    "provider": {
        "id": 1,
        "name": "SecureLife Insurance",
        "code": "SLI"
    },
    "attributes": [
        {
            "id": 1,
            "name": "Vehicle Make",
            "value": "Toyota"
        }
    ],
    "created_at": "2024-01-01T12:00:00.000000Z"
}
```

### Update Policy

Update policy information.

**PUT** `/policies/{id}`

**Request Body:**
```json
{
    "premium_amount": 550.00,
    "end_date": "2025-12-31",
    "status": "expired"
}
```

**Response (200):**
```json
{
    "id": 1,
    "premium_amount": "550.00",
    "end_date": "2025-12-31",
    "status": "expired",
    "updated_at": "2024-01-01T12:30:00.000000Z"
}
```

### Delete Policy

Delete policy by ID.

**DELETE** `/policies/{id}`

**Response (204):** *No content*

---

## Policy Attributes 🔒 Admin Only

### List Policy Attributes

Get list of policy attributes.

**GET** `/policy_attributes` 🔒

**Response (200):**
```json
{
    "data": [
        {
            "id": 1,
            "name": "Vehicle Make",
            "type": "string",
            "required": true,
            "options": ["Toyota", "Honda", "Ford", "BMW"],
            "created_at": "2024-01-01T12:00:00.000000Z"
        }
    ]
}
```

### Create Policy Attribute

Create new policy attribute.

**POST** `/policy_attributes` 🔒

**Request Body:**
```json
{
    "name": "Vehicle Make",
    "type": "string",
    "required": true,
    "options": ["Toyota", "Honda", "Ford", "BMW"]
}
```

**Response (201):**
```json
{
    "id": 1,
    "name": "Vehicle Make",
    "type": "string",
    "required": true,
    "created_at": "2024-01-01T12:00:00.000000Z"
}
```

### Get Policy Attribute

Get policy attribute by ID.

**GET** `/policy_attributes/{id}` 🔒

**Response (200):**
```json
{
    "id": 1,
    "name": "Vehicle Make",
    "type": "string",
    "required": true,
    "options": ["Toyota", "Honda", "Ford", "BMW"],
    "values_count": 25,
    "created_at": "2024-01-01T12:00:00.000000Z"
}
```

### Update Policy Attribute

Update policy attribute.

**PUT** `/policy_attributes/{id}` 🔒

**Request Body:**
```json
{
    "name": "Vehicle Make Updated",
    "options": ["Toyota", "Honda", "Ford", "BMW", "Mercedes"]
}
```

**Response (200):**
```json
{
    "id": 1,
    "name": "Vehicle Make Updated",
    "options": ["Toyota", "Honda", "Ford", "BMW", "Mercedes"],
    "updated_at": "2024-01-01T12:30:00.000000Z"
}
```

### Delete Policy Attribute

Delete policy attribute by ID.

**DELETE** `/policy_attributes/{id}` 🔒

**Response (204):** *No content*

---

## Policy Attribute Values 🔒 Admin Only

### List Policy Attribute Values

Get list of policy attribute values.

**GET** `/policy_attribute_values` 🔒

**Response (200):**
```json
{
    "data": [
        {
            "id": 1,
            "policy_id": 1,
            "policy_attribute_id": 1,
            "value": "Toyota",
            "created_at": "2024-01-01T12:00:00.000000Z"
        }
    ]
}
```

### Create Policy Attribute Value

Create new policy attribute value.

**POST** `/policy_attribute_values` 🔒

**Request Body:**
```json
{
    "policy_id": 1,
    "policy_attribute_id": 1,
    "value": "Toyota"
}
```

**Response (201):**
```json
{
    "id": 1,
    "policy_id": 1,
    "policy_attribute_id": 1,
    "value": "Toyota",
    "created_at": "2024-01-01T12:00:00.000000Z"
}
```

### Get Policy Attribute Value

Get policy attribute value by ID.

**GET** `/policy_attribute_values/{id}` 🔒

**Response (200):**
```json
{
    "id": 1,
    "policy_id": 1,
    "policy_attribute_id": 1,
    "value": "Toyota",
    "policy": {
        "id": 1,
        "policy_number": "POL123456"
    },
    "attribute": {
        "id": 1,
        "name": "Vehicle Make"
    },
    "created_at": "2024-01-01T12:00:00.000000Z"
}
```

### Update Policy Attribute Value

Update policy attribute value.

**PUT** `/policy_attribute_values/{id}` 🔒

**Request Body:**
```json
{
    "value": "Honda"
}
```

**Response (200):**
```json
{
    "id": 1,
    "value": "Honda",
    "updated_at": "2024-01-01T12:30:00.000000Z"
}
```

### Delete Policy Attribute Value

Delete policy attribute value by ID.

**DELETE** `/policy_attribute_values/{id}` 🔒

**Response (204):** *No content*

---

## Notifications

### Send Welcome Notification

Send welcome notification to user.

**POST** `/notifications/welcome`

**Request Body:**
```json
{
    "user_id": 1
}
```

**Response (200):**
```json
{
    "success": true,
    "message": "Welcome notification sent successfully"
}
```

### Send Policy Created Notification

Send policy created notification to customer.

**POST** `/notifications/policy-created`

**Request Body:**
```json
{
    "policy_id": 1
}
```

**Response (200):**
```json
{
    "success": true,
    "message": "Policy created notification sent successfully"
}
```

### Send Payment Confirmation

Send payment confirmation notification.

**POST** `/notifications/payment-confirmation`

**Request Body:**
```json
{
    "payment_id": 1
}
```

**Response (200):**
```json
{
    "success": true,
    "message": "Payment confirmation sent successfully"
}
```

### Send Bulk Notifications

Send notifications to multiple users.

**POST** `/notifications/bulk`

**Request Body:**
```json
{
    "user_ids": [1, 2, 3, 4, 5],
    "notification_type": "welcome",
    "data": {
        "custom_message": "Welcome to our platform!"
    }
}
```

**Response (200):**
```json
{
    "success": true,
    "message": "Bulk notifications processed",
    "data": {
        "sent": 4,
        "failed": 1,
        "errors": [
            {
                "user_id": 5,
                "error": "User not found"
            }
        ]
    }
}
```

### Get Notification History

Get notification history for user.

**GET** `/notifications/history/{userId}`

**Response (200):**
```json
{
    "success": true,
    "message": "Notification history retrieved successfully",
    "data": {
        "user_id": 1,
        "notifications": [
            {
                "id": 1,
                "type": "App\\Notifications\\WelcomeNotification",
                "read_at": null,
                "created_at": "2024-01-01T12:00:00.000000Z",
                "data": {
                    "message": "Welcome to NaiCover!"
                }
            }
        ]
    }
}
```

---

## Error Responses

### Standard Error Format

All errors follow this format:

```json
{
    "message": "Error description",
    "errors": {
        "field_name": ["Validation error message"]
    }
}
```

### HTTP Status Codes

- **200**: Success
- **201**: Created successfully
- **204**: No content (successful deletion)
- **400**: Bad request
- **401**: Unauthorized
- **403**: Forbidden
- **404**: Not found
- **422**: Validation error
- **500**: Internal server error

### Common Errors

#### Authentication Required (401)
```json
{
    "message": "Unauthenticated."
}
```

#### Insufficient Permissions (403)
```json
{
    "message": "This action is unauthorized."
}
```

#### Resource Not Found (404)
```json
{
    "message": "Resource not found"
}
```

#### Validation Error (422)
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "email": ["The email field is required."],
        "password": ["The password must be at least 8 characters."]
    }
}
```

---

## Rate Limiting

API endpoints are rate limited to prevent abuse:

- **Authentication endpoints**: 5 requests per minute
- **General API endpoints**: 60 requests per minute
- **Bulk operations**: 10 requests per minute

Rate limit headers are included in responses:

```http
X-RateLimit-Limit: 60
X-RateLimit-Remaining: 59
X-RateLimit-Reset: 1640995200
```

---

## Pagination

List endpoints support pagination with these parameters:

- `page` (integer): Page number (default: 1)
- `per_page` (integer): Items per page (default: 15, max: 100)

Pagination metadata is included in responses:

```json
{
    "data": [...],
    "links": {
        "first": "http://api.example.com/endpoint?page=1",
        "last": "http://api.example.com/endpoint?page=10",
        "prev": null,
        "next": "http://api.example.com/endpoint?page=2"
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 10,
        "path": "http://api.example.com/endpoint",
        "per_page": 15,
        "to": 15,
        "total": 150
    }
}
```

---

## Testing the API

### Using cURL

```bash
# Register user
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Test User",
    "email": "test@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'

# Login and get token
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "test@example.com",
    "password": "password123"
  }'

# Use token for authenticated requests
curl -X GET http://localhost:8000/api/user \
  -H "Authorization: Bearer YOUR_ACCESS_TOKEN"
```

### Using Postman

1. Import the API collection (if available)
2. Set up environment variables:
   - `base_url`: `http://localhost:8000/api`
   - `access_token`: Your bearer token
3. Use `{{base_url}}` and `{{access_token}}` in requests

---

## SDKs and Libraries

### PHP SDK

```php
use NaiCoverAPI\Client;

$client = new Client([
    'base_url' => 'http://localhost:8000/api',
    'access_token' => 'your_access_token'
]);

$policies = $client->policies()->list([
    'status' => 'active',
    'per_page' => 20
]);
```

### JavaScript SDK

```javascript
import NaiCoverAPI from 'naicoverapi-js';

const client = new NaiCoverAPI({
    baseURL: 'http://localhost:8000/api',
    accessToken: 'your_access_token'
});

const policies = await client.policies.list({
    status: 'active',
    per_page: 20
});
```

---

For more detailed information, see the [complete API documentation](https://docs.naicoverapi.com) or check out the [Postman Collection](https://postman.naicoverapi.com).