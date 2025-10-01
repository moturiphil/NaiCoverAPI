# Quick Setup Guide

Get NaiCover API up and running in 5 minutes.

## Prerequisites

- PHP 8.2+
- Composer
- Node.js 16+

## Installation

```bash
# 1. Clone the repository
git clone https://github.com/moturiphil/NaiCoverAPI.git
cd NaiCoverAPI

# 2. Install dependencies
composer install
npm install

# 3. Environment setup
cp .env.example .env
php artisan key:generate

# 4. Database setup
touch database/database.sqlite
php artisan migrate --seed

# 5. Passport setup
php artisan passport:install

# 6. Start the server
php artisan serve
```

## Test the Installation

```bash
# Test API health
curl http://localhost:8000/api/test

# Register a user
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Test User",
    "email": "test@example.com", 
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

## Development Workflow

```bash
# Run tests
php artisan test

# Format code
vendor/bin/pint

# Start development with all services
composer run dev
```

## Next Steps

- [Read the full API documentation](API_DOCUMENTATION.md)
- [Check the contributing guide](CONTRIBUTING.md)
- [Review API examples](docs/api-examples.md)
- [Learn about deployment](docs/deployment.md)

## Need Help?

- Check [existing issues](https://github.com/moturiphil/NaiCoverAPI/issues)
- Read the [troubleshooting section](docs/deployment.md#troubleshooting)
- Contact the development team

## Common Issues

**"Connection refused" error**: Make sure PHP and database are running.

**"Passport keys not found"**: Run `php artisan passport:install` to generate keys.

**"Permission denied"**: Set proper permissions with `chmod -R 775 storage bootstrap/cache`.

**Tests failing**: Make sure you have a separate test database or SQLite file.

Happy coding! 🚀