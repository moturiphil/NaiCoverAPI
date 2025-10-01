# NaiCover API Documentation

Welcome to the complete documentation for NaiCover API - a comprehensive insurance platform.

## Quick Links

### Getting Started
- [**Quick Setup Guide**](../QUICKSTART.md) - Get running in 5 minutes
- [**Complete Setup Guide**](../README.md) - Full installation and configuration
- [**Contributing Guide**](../CONTRIBUTING.md) - How to contribute to the project

### API Documentation
- [**Complete API Documentation**](../API_DOCUMENTATION.md) - All endpoints with examples
- [**API Examples**](api-examples.md) - Practical code examples and integration patterns
- [**Mailing Service**](../MAILING_SERVICE.md) - Email notification system

### Deployment & Operations
- [**Deployment Guide**](deployment.md) - Production deployment instructions
- [**Environment Configuration**](../README.md#configuration) - Configuration options
- [**Security Guidelines**](../README.md#security) - Security best practices

## Key Features

NaiCover API provides:

- 🔐 **Authentication & Authorization** - Laravel Passport with role-based access
- 👥 **User Management** - Customers, agents, and admin users
- 📋 **Policy Management** - Insurance policies with custom attributes
- 💳 **Payment Processing** - Payment handling and confirmations
- 📧 **Notification System** - Email notifications and communication
- 🏢 **Multi-Provider Support** - Support for multiple insurance providers

## Architecture Overview

```
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│   Frontend      │    │   API Gateway   │    │   Database      │
│   Applications  │◄──►│   (Laravel)     │◄──►│   (MySQL/SQLite)│
└─────────────────┘    └─────────────────┘    └─────────────────┘
                                │
                       ┌─────────────────┐
                       │   Queue System  │
                       │   (Redis/DB)    │
                       └─────────────────┘
```

## API Endpoints Overview

### Authentication
- `POST /api/register` - User registration
- `POST /api/login` - User authentication
- `POST /api/logout` - Logout user
- `GET /api/user` - Get authenticated user

### Customer Management
- `GET /api/customers` - List customers
- `POST /api/customers` - Create customer
- `GET /api/customers/{id}` - Get customer
- `PUT /api/customers/{id}` - Update customer

### Policy Management
- `GET /api/policies` - List policies
- `POST /api/policies` - Create policy
- `GET /api/policies/{id}` - Get policy details
- `PUT /api/policies/{id}` - Update policy

### Notifications
- `POST /api/notifications/welcome` - Send welcome notification
- `POST /api/notifications/policy-created` - Policy created notification
- `POST /api/notifications/bulk` - Bulk notifications

[View complete endpoint list →](../API_DOCUMENTATION.md#api-endpoints)

## Technology Stack

- **Framework**: Laravel 12
- **PHP**: 8.4.6
- **Authentication**: Laravel Passport (OAuth2)
- **Database**: MySQL/PostgreSQL/SQLite
- **Queue**: Redis/Database
- **Email**: SMTP/SendGrid/Mailtrap
- **Testing**: PHPUnit
- **Code Style**: Laravel Pint

## Development Tools

### Recommended IDE Extensions
- PHP Intelephense
- Laravel Extra Intellisense
- Laravel Blade Spacer
- PHPUnit Test Explorer

### Useful Artisan Commands
```bash
php artisan make:controller PolicyController --api
php artisan make:model Policy -mfs
php artisan make:test PolicyTest --feature
php artisan queue:work
php artisan tinker
```

## Support

### Getting Help
1. Check the documentation first
2. Search [existing issues](https://github.com/moturiphil/NaiCoverAPI/issues)
3. Create a new issue with detailed information
4. Contact the development team

### Contributing
We welcome contributions! Please read our [Contributing Guide](../CONTRIBUTING.md) for:
- Code standards and style guide
- Testing requirements
- Pull request process
- Development workflow

### Community
- GitHub Issues for bug reports and feature requests
- GitHub Discussions for questions and community support
- Code reviews and collaboration

## License

This project is licensed under the MIT License - see the [LICENSE](../LICENSE) file for details.

---

**Ready to get started?** Follow our [Quick Setup Guide](../QUICKSTART.md) to get the API running in minutes!