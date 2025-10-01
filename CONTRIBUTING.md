# Contributing to NaiCover API

Thank you for your interest in contributing to NaiCover API! We welcome contributions from developers of all skill levels and backgrounds.

## Table of Contents

- [Code of Conduct](#code-of-conduct)
- [Getting Started](#getting-started)
- [Development Setup](#development-setup)
- [Development Workflow](#development-workflow)
- [Coding Standards](#coding-standards)
- [Testing](#testing)
- [Submitting Changes](#submitting-changes)
- [Reporting Issues](#reporting-issues)
- [Feature Requests](#feature-requests)
- [Documentation](#documentation)
- [Community](#community)

## Code of Conduct

This project and everyone participating in it is governed by our [Code of Conduct](CODE_OF_CONDUCT.md). By participating, you are expected to uphold this code. Please report unacceptable behavior to the project maintainers.

## Getting Started

### Prerequisites

Before contributing, make sure you have:

- PHP 8.2 or higher
- Composer
- Node.js 16+ and npm
- Git
- A GitHub account
- Basic knowledge of Laravel and PHP

### Setting Up Your Development Environment

1. **Fork the repository** on GitHub
2. **Clone your fork** locally:
   ```bash
   git clone https://github.com/your-username/NaiCoverAPI.git
   cd NaiCoverAPI
   ```

3. **Add the upstream remote**:
   ```bash
   git remote add upstream https://github.com/moturiphil/NaiCoverAPI.git
   ```

4. **Install dependencies**:
   ```bash
   composer install
   npm install
   ```

5. **Set up environment**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   touch database/database.sqlite
   php artisan migrate
   php artisan db:seed
   ```

6. **Install Passport**:
   ```bash
   php artisan passport:install
   ```

## Development Setup

### Branch Strategy

We use the following branch structure:

- **main**: Production-ready code
- **develop**: Integration branch for features
- **feature/***: New features
- **bugfix/***: Bug fixes
- **hotfix/***: Critical production fixes

### Setting Up Your Feature Branch

```bash
# Update your main branch
git checkout main
git pull upstream main

# Create and switch to a new feature branch
git checkout -b feature/your-feature-name

# Or for bug fixes
git checkout -b bugfix/issue-description
```

### Development Server

Start the development server:

```bash
# Start Laravel server with queue worker and other services
composer run dev

# Or individually:
php artisan serve
php artisan queue:work
```

## Development Workflow

### 1. Choose an Issue or Feature

- Look through [open issues](https://github.com/moturiphil/NaiCoverAPI/issues)
- Check if someone is already working on it
- Comment on the issue to let others know you're working on it
- For major features, discuss the approach first

### 2. Development Process

1. **Write failing tests** first (TDD approach preferred)
2. **Implement your changes** following our coding standards
3. **Make sure tests pass**: `php artisan test`
4. **Run code formatting**: `vendor/bin/pint`
5. **Update documentation** if needed

### 3. Testing Your Changes

```bash
# Run all tests
php artisan test

# Run specific test types
php artisan test --testsuite=Feature
php artisan test --testsuite=Unit

# Run with coverage
php artisan test --coverage

# Test specific functionality
php artisan test --filter=NotificationApiTest
```

### 4. Before Submitting

- [ ] All tests pass
- [ ] Code follows style guidelines
- [ ] Documentation is updated
- [ ] Commit messages are clear and descriptive
- [ ] No merge conflicts with main branch

## Coding Standards

### PHP Standards

We follow **PSR-12** coding standard with Laravel-specific conventions:

#### General Rules

- Use PHP 8+ features where appropriate
- Follow Laravel naming conventions
- Use type hints for method parameters and return types
- Use constructor property promotion for PHP 8+

```php
<?php

// Good
public function __construct(
    private UserService $userService,
    private NotificationService $notificationService
) {}

// Good - Type declarations
public function createPolicy(CreatePolicyRequest $request): Policy
{
    return $this->policyService->create($request->validated());
}
```

#### Laravel Conventions

- **Controllers**: Use resource controllers when possible
- **Models**: Use Eloquent relationships properly
- **Validation**: Always use Form Request classes for validation
- **Services**: Use service classes for complex business logic

```php
<?php

// Good - Form Request validation
class CreatePolicyRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'customer_id' => ['required', 'exists:customers,id'],
            'policy_number' => ['required', 'string', 'unique:policies'],
            'premium_amount' => ['required', 'numeric', 'min:0'],
        ];
    }
}

// Good - Service usage in controller
class PolicyController extends Controller
{
    public function store(CreatePolicyRequest $request): JsonResponse
    {
        $policy = $this->policyService->create($request->validated());
        
        return response()->json([
            'success' => true,
            'data' => new PolicyResource($policy),
        ], 201);
    }
}
```

### Database Conventions

- Use descriptive migration names
- Include rollback methods
- Use proper foreign key constraints
- Add indexes for commonly queried fields

```php
<?php

// Good migration
public function up(): void
{
    Schema::create('policies', function (Blueprint $table) {
        $table->id();
        $table->string('policy_number')->unique();
        $table->foreignId('customer_id')->constrained()->onDelete('cascade');
        $table->foreignId('agent_id')->constrained();
        $table->decimal('premium_amount', 10, 2);
        $table->enum('status', ['active', 'expired', 'cancelled']);
        $table->timestamps();
        
        $table->index(['status', 'created_at']);
    });
}
```

### Code Formatting

We use **Laravel Pint** for automatic code formatting:

```bash
# Format all files
vendor/bin/pint

# Format specific files
vendor/bin/pint app/Http/Controllers/PolicyController.php

# Check formatting without fixing
vendor/bin/pint --test
```

## Testing

### Test Structure

- **Unit Tests**: Test individual classes/methods in isolation
- **Feature Tests**: Test complete features/API endpoints
- **Integration Tests**: Test multiple components working together

### Writing Tests

#### Feature Test Example

```php
<?php

class PolicyApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->adminUser = User::factory()->create();
        $this->adminUser->assignRole('admin');
        
        Passport::actingAs($this->adminUser);
    }

    public function test_can_create_policy(): void
    {
        $customer = Customer::factory()->create();
        
        $response = $this->postJson('/api/policies', [
            'customer_id' => $customer->id,
            'policy_number' => 'POL123456',
            'premium_amount' => 1000.00,
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'id',
                    'policy_number',
                    'premium_amount',
                ],
            ]);
    }
}
```

#### Unit Test Example

```php
<?php

class PolicyServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_calculate_premium(): void
    {
        $policyService = new PolicyService();
        $baseAmount = 1000;
        $riskFactor = 1.2;

        $premium = $policyService->calculatePremium($baseAmount, $riskFactor);

        $this->assertEquals(1200, $premium);
    }
}
```

### Test Guidelines

- Use descriptive test method names
- Follow the Arrange-Act-Assert pattern
- Use factories for test data
- Mock external services
- Test both success and failure scenarios

## Submitting Changes

### Commit Messages

Use clear, descriptive commit messages following this format:

```
type: brief description (50 chars or less)

More detailed explanation if needed. Explain what and why,
not how. Wrap at 72 characters.

- Bullet points are okay
- Use imperative mood: "Add feature" not "Added feature"

Fixes #123
```

#### Commit Types

- `feat`: New features
- `fix`: Bug fixes
- `docs`: Documentation updates
- `style`: Code formatting (no functional changes)
- `refactor`: Code refactoring
- `test`: Adding or updating tests
- `chore`: Maintenance tasks

#### Examples

```bash
feat: add policy search endpoint

Implement search functionality for policies with filters
for status, date range, and customer. Includes pagination
and sorting capabilities.

- Add PolicySearchRequest validation
- Implement search service with filters
- Add comprehensive tests
- Update API documentation

Closes #45
```

### Pull Request Process

1. **Update your branch**:
   ```bash
   git checkout main
   git pull upstream main
   git checkout your-branch
   git rebase main
   ```

2. **Push your changes**:
   ```bash
   git push origin your-branch
   ```

3. **Create Pull Request** with:
   - Clear title and description
   - Link to related issues
   - Screenshots for UI changes
   - List of changes made
   - Testing instructions

4. **Pull Request Template**:
   ```markdown
   ## Description
   Brief description of changes

   ## Type of Change
   - [ ] Bug fix
   - [ ] New feature
   - [ ] Breaking change
   - [ ] Documentation update

   ## Related Issues
   Fixes #123

   ## Testing
   - [ ] Tests pass
   - [ ] New tests added
   - [ ] Manual testing completed

   ## Screenshots
   (If applicable)
   ```

### Review Process

- All PRs require at least one review
- Address reviewer feedback promptly
- Keep discussions constructive
- Make requested changes in new commits
- Squash commits before merging if requested

## Reporting Issues

### Bug Reports

Use the bug report template and include:

- Laravel and PHP versions
- Steps to reproduce
- Expected vs actual behavior
- Error messages/logs
- Environment details

### Security Issues

**Do not** open public issues for security vulnerabilities. Instead:

- Email security issues to the maintainers
- Include detailed reproduction steps
- Allow time for fix before disclosure

## Feature Requests

- Check existing feature requests first
- Provide clear use case and requirements
- Include implementation suggestions
- Be prepared to contribute or sponsor development

## Documentation

### Types of Documentation

- **API Documentation**: Endpoint descriptions and examples
- **Code Comments**: PHPDoc blocks for complex logic
- **README Updates**: Installation and usage instructions
- **Wiki Pages**: Detailed guides and tutorials

### Documentation Standards

- Use clear, simple language
- Include code examples
- Keep documentation up-to-date with code changes
- Follow Markdown formatting guidelines

## Community

### Getting Help

- **Documentation**: Check existing docs first
- **Issues**: Search closed issues for solutions
- **Discussions**: Use GitHub Discussions for questions
- **Discord/Slack**: Join our community chat (if available)

### Ways to Contribute

- **Code**: Bug fixes, new features, performance improvements
- **Documentation**: Improve existing docs, write tutorials
- **Testing**: Report bugs, improve test coverage
- **Community**: Help other contributors, answer questions

### Recognition

Contributors will be:

- Listed in CONTRIBUTORS.md
- Mentioned in release notes
- Given appropriate GitHub repository permissions
- Invited to maintainer discussions for regular contributors

## Development Tips

### Useful Commands

```bash
# Development helpers
php artisan tinker              # Interactive shell
php artisan route:list          # List all routes
php artisan make:test PolicyTest --feature  # Create feature test

# Database
php artisan migrate:fresh --seed   # Reset database
php artisan db:seed --class=PolicySeeder   # Run specific seeder

# Debugging
php artisan telescope:install   # Install debugging tool
php artisan queue:work --verbose   # Verbose queue processing
```

### IDE Setup

Recommended VS Code extensions:

- PHP Intelephense
- Laravel Extra Intellisense  
- Laravel Blade Spacer
- PHPUnit Test Explorer
- GitLens

### Performance Considerations

- Use database indexes appropriately
- Implement eager loading for relationships
- Cache expensive operations
- Use queues for time-consuming tasks
- Profile database queries

## Questions?

If you have questions not covered here:

1. Check existing documentation
2. Search closed issues and discussions
3. Ask in GitHub Discussions
4. Contact maintainers directly

Thank you for contributing to NaiCover API! 🚀