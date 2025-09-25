# NaiCover API

A comprehensive insurance platform API built with Laravel 12, providing robust endpoints for managing insurance policies, customers, agents, payments, and notifications.

<p align="center">
<a href="#"><img src="https://img.shields.io/badge/Laravel-12-red.svg" alt="Laravel 12"></a>
<a href="#"><img src="https://img.shields.io/badge/PHP-8.4.6-blue.svg" alt="PHP 8.4.6"></a>
<a href="#"><img src="https://img.shields.io/badge/License-MIT-green.svg" alt="MIT License"></a>
</p>

## Table of Contents

- [About](#about)
- [Features](#features)
- [Requirements](#requirements)
- [Installation](#installation)
- [Configuration](#configuration)
- [Authentication](#authentication)
- [API Endpoints](#api-endpoints)
- [Database](#database)
- [Testing](#testing)
- [Deployment](#deployment)
- [Contributing](#contributing)
- [Security](#security)
- [License](#license)

## About

NaiCover API is a modern insurance platform that provides comprehensive API endpoints for managing:

- **Customer Management**: User registration, profiles, and customer data
- **Agent Management**: Insurance agent profiles and management
- **Policy Management**: Insurance policies, attributes, and types
- **Payment Processing**: Payment handling and confirmation
- **Notification System**: Email notifications and communication
- **Insurance Providers**: Multi-provider insurance platform support

## Features

- 🔐 **Laravel Passport Authentication** with role-based access control
- 📧 **Comprehensive Notification System** with email support
- 🏢 **Multi-Provider Insurance Platform** support
- 💳 **Payment Processing** integration
- 📊 **Policy Management** with custom attributes
- 👥 **Role-Based Access Control** using Spatie Laravel Permission
- 🧪 **Comprehensive Testing Suite** with PHPUnit
- 📚 **API Documentation** with detailed endpoints
- 🔄 **Queue System** for background processing
- 🗄️ **SQLite/MySQL Database** support

## Requirements

- **PHP**: >= 8.2
- **Laravel**: ^12.0
- **Composer**: Latest version
- **Node.js**: >= 16.x (for asset compilation)
- **Database**: SQLite (default) or MySQL/PostgreSQL
- **PHP Extensions**: openssl, pdo, mbstring, tokenizer, xml, ctype, json, bcmath

## Installation

### 1. Clone the Repository

```bash
git clone https://github.com/moturiphil/NaiCoverAPI.git
cd NaiCoverAPI
```

### 2. Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies (if needed for frontend assets)
npm install
```

### 3. Environment Setup

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Database Setup

```bash
# Create SQLite database (default)
touch database/database.sqlite

# Or configure MySQL/PostgreSQL in .env file
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=naicoverapi
# DB_USERNAME=your_username
# DB_PASSWORD=your_password

# Run migrations
php artisan migrate

# Seed database with initial data
php artisan db:seed
```

### 5. Laravel Passport Setup

```bash
# Install Passport
php artisan passport:install

# Generate encryption keys (will be prompted during first run)
php artisan passport:keys
```

### 6. Configure Permissions

```bash
# Set up roles and permissions
php artisan db:seed --class=RoleSeeder
```

## Configuration

### Environment Variables

Update your `.env` file with the following configurations:

```env
APP_NAME="NaiCover API"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database Configuration
DB_CONNECTION=sqlite
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=naicoverapi

# Mail Configuration (Mailtrap for development)
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_FROM_ADDRESS="notifications@naicoverapi.com"
MAIL_FROM_NAME="${APP_NAME}"

# Queue Configuration
QUEUE_CONNECTION=database

# Cache Configuration
CACHE_STORE=database
```

### Production Configuration

For production environments:

1. Set `APP_ENV=production` and `APP_DEBUG=false`
2. Configure a robust database (MySQL/PostgreSQL)
3. Use a professional email service (SendGrid, AWS SES, etc.)
4. Set up proper queue workers with Supervisor
5. Configure Redis for caching and sessions

## Authentication

The API uses **Laravel Passport** for OAuth2 authentication with role-based access control.

### Available Roles

- **Admin**: Full system access
- **Agent**: Insurance agent permissions
- **Customer**: Customer-level access

### Authentication Flow

1. **Register/Login**: Obtain access token
2. **Include Token**: Add `Authorization: Bearer {token}` header
3. **Role Verification**: Endpoints check user roles automatically

### Authentication Endpoints

```http
POST /api/register          # User registration
POST /api/login            # User login
POST /api/logout           # User logout (authenticated)
GET  /api/user             # Get authenticated user info
```

## API Endpoints

### Public Endpoints

```http
GET  /api/test                    # API health check
POST /api/register                # User registration  
POST /api/login                   # User authentication
```

### User Management

```http
GET    /api/users                 # List all users
POST   /api/users                 # Create new user
GET    /api/users/{id}            # Get specific user
PUT    /api/users/{id}            # Update user
DELETE /api/users/{id}            # Delete user
```

### Customer Management

```http
GET    /api/customers             # List customers (Admin only)
POST   /api/customers             # Create customer (Admin only)
GET    /api/customers/{id}        # Get customer (Admin only)
PUT    /api/customers/{id}        # Update customer (Admin only)
DELETE /api/customers/{id}        # Delete customer (Admin only)
```

### Agent Management

```http
GET    /api/agents                # List agents
POST   /api/agents                # Create agent
GET    /api/agents/{id}           # Get agent
PUT    /api/agents/{id}           # Update agent
DELETE /api/agents/{id}           # Delete agent
```

### Insurance & Policy Management

```http
GET    /api/insurance_providers   # List insurance providers
POST   /api/insurance_providers   # Create provider
GET    /api/insurance_providers/{id} # Get provider
PUT    /api/insurance_providers/{id} # Update provider
DELETE /api/insurance_providers/{id} # Delete provider

GET    /api/policies              # List policies
POST   /api/policies              # Create policy
GET    /api/policies/{id}         # Get policy
PUT    /api/policies/{id}         # Update policy
DELETE /api/policies/{id}         # Delete policy
```

### Policy Attributes

```http
GET    /api/policy_attributes     # List policy attributes (Admin only)
POST   /api/policy_attributes     # Create attribute (Admin only)
GET    /api/policy_attributes/{id} # Get attribute (Admin only)
PUT    /api/policy_attributes/{id} # Update attribute (Admin only)
DELETE /api/policy_attributes/{id} # Delete attribute (Admin only)

GET    /api/policy_attribute_values # List attribute values (Admin only)
POST   /api/policy_attribute_values # Create attribute value (Admin only)
GET    /api/policy_attribute_values/{id} # Get attribute value (Admin only)
PUT    /api/policy_attribute_values/{id} # Update attribute value (Admin only)
DELETE /api/policy_attribute_values/{id} # Delete attribute value (Admin only)
```

### Notifications

```http
POST /api/notifications/welcome              # Send welcome notification
POST /api/notifications/policy-created       # Send policy created notification
POST /api/notifications/payment-confirmation # Send payment confirmation
POST /api/notifications/bulk                 # Send bulk notifications
GET  /api/notifications/history/{userId}     # Get notification history
```

### Request/Response Examples

#### User Registration

**Request:**
```http
POST /api/register
Content-Type: application/json

{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

**Response:**
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

#### Send Welcome Notification

**Request:**
```http
POST /api/notifications/welcome
Authorization: Bearer {token}
Content-Type: application/json

{
    "user_id": 123
}
```

**Response:**
```json
{
    "success": true,
    "message": "Welcome notification sent successfully"
}
```

## Database

The application uses Laravel migrations for database management.

### Key Models

- **User**: System users with role-based access
- **Customer**: Insurance customers
- **Agent**: Insurance agents
- **Policy**: Insurance policies
- **Insurance**: Insurance providers
- **Payment**: Payment records
- **PolicyAttribute**: Custom policy attributes
- **Notification**: Notification history

### Relationships

- User ↔ Customer (1:1)
- Customer ↔ Policy (1:Many)
- Agent ↔ Policy (1:Many)
- Insurance ↔ Policy (1:Many)
- Policy ↔ PolicyAttributeValues (Many:Many)

### Migrations

```bash
# Run all migrations
php artisan migrate

# Reset and re-run migrations
php artisan migrate:fresh

# Run with seeders
php artisan migrate:fresh --seed

# Rollback migrations
php artisan migrate:rollback
```

## Testing

The project includes comprehensive test suites covering features and units.

### Running Tests

```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --testsuite=Feature
php artisan test --testsuite=Unit

# Run with coverage
php artisan test --coverage

# Run specific test file
php artisan test tests/Feature/AuthTest.php
```

### Test Database

Tests use an in-memory SQLite database configured in `phpunit.xml`.

### Available Test Suites

- **AuthTest**: Authentication and authorization testing
- **NotificationApiTest**: Notification API endpoint testing  
- **NotificationServiceTest**: Notification service testing
- **PolicyTest**: Policy management testing
- **CustomerTest**: Customer management testing

## Deployment

### Development Server

```bash
# Start Laravel development server
php artisan serve

# Start with queue worker and other services
composer run dev
```

### Production Deployment

1. **Server Requirements**
   - PHP 8.2+ with required extensions
   - Web server (Nginx/Apache)
   - Process manager (Supervisor for queues)

2. **Deployment Steps**
   ```bash
   # Clone and install
   git clone https://github.com/moturiphil/NaiCoverAPI.git
   cd NaiCoverAPI
   composer install --optimize-autoloader --no-dev
   
   # Configure environment
   cp .env.example .env
   php artisan key:generate
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   
   # Database setup
   php artisan migrate --force
   php artisan passport:keys
   
   # Set permissions
   chown -R www-data:www-data storage bootstrap/cache
   chmod -R 755 storage bootstrap/cache
   ```

3. **Queue Workers**
   ```bash
   # Setup supervisor for queue processing
   sudo nano /etc/supervisor/conf.d/naicoverapi-worker.conf
   ```

4. **Web Server Configuration**
   - Point document root to `public/` directory
   - Configure SSL certificates
   - Set up proper PHP-FPM configuration

### Environment-Specific Notes

- **Development**: Use Mailtrap for email testing
- **Staging**: Mirror production but with debug enabled
- **Production**: Disable debug, use robust email service, set up monitoring

## Contributing

We welcome contributions! Please see our [Contributing Guide](CONTRIBUTING.md) for details.

### Quick Start for Contributors

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Make your changes and add tests
4. Run the test suite (`php artisan test`)
5. Run code formatting (`vendor/bin/pint`)
6. Commit your changes (`git commit -m 'Add amazing feature'`)
7. Push to the branch (`git push origin feature/amazing-feature`)
8. Open a Pull Request

## Security

### Security Vulnerabilities

If you discover a security vulnerability, please send an email to the maintainers. All security vulnerabilities will be promptly addressed.

### Security Features

- Laravel Passport OAuth2 implementation
- Role-based access control
- Input validation and sanitization
- CSRF protection
- SQL injection protection via Eloquent ORM
- Secure password hashing

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Support

For support and questions:

- Check the [Documentation](docs/)
- Review existing [Issues](https://github.com/moturiphil/NaiCoverAPI/issues)
- Contact the development team

## Additional Documentation

- [Mailing Service Documentation](MAILING_SERVICE.md)
- [API Examples](docs/api-examples.md)
- [Contributing Guide](CONTRIBUTING.md)
- [Deployment Guide](docs/deployment.md)