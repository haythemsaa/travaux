# Travaux Pro - Tests

This directory contains all automated tests for the Travaux Pro platform.

## Test Structure

```
tests/
├── Unit/           # Unit tests for individual classes
├── Integration/    # Integration tests for API endpoints
├── Feature/        # Feature tests for complete workflows
└── README.md       # This file
```

## Running Tests

### Prerequisites

```bash
composer install
```

### Run All Tests

```bash
php vendor/bin/phpunit
```

### Run Specific Test Suite

```bash
# Unit tests only
php vendor/bin/phpunit --testsuite Unit

# Integration tests only
php vendor/bin/phpunit --testsuite Integration

# Feature tests only
php vendor/bin/phpunit --testsuite Feature
```

### Run With Coverage

```bash
php vendor/bin/phpunit --coverage-html coverage/
```

## Writing Tests

### Unit Test Example

```php
<?php
namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\User;

class UserTest extends TestCase
{
    public function testUserCreation()
    {
        $user = new User();
        $user->email = 'test@example.com';

        $this->assertEquals('test@example.com', $user->email);
    }
}
```

### Integration Test Example

```php
<?php
namespace Tests\Integration;

use PHPUnit\Framework\TestCase;

class AuthAPITest extends TestCase
{
    public function testLogin()
    {
        $response = $this->post('/api/auth/login', [
            'email' => 'test@example.com',
            'password' => 'password123'
        ]);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('token', $response->getData());
    }
}
```

## Test Database

Tests use a separate test database configured in `phpunit.xml`.

```xml
<php>
    <env name="DB_NAME" value="travaux_pro_test"/>
    <env name="DB_USER" value="test_user"/>
    <env name="DB_PASSWORD" value="test_password"/>
</php>
```

## Code Coverage Goals

- **Minimum:** 70% overall coverage
- **Target:** 85% overall coverage
- **Critical paths:** 95% coverage (auth, payments, security)

## Continuous Integration

Tests are automatically run on:
- Every pull request
- Every push to main branch
- Nightly builds

## Best Practices

1. **Test Naming:** Use descriptive names that explain what's being tested
2. **Arrange-Act-Assert:** Structure tests clearly
3. **Independence:** Tests should not depend on each other
4. **Clean Up:** Always clean up test data after tests
5. **Mock External Services:** Use mocks for Stripe, Firebase, etc.

## Mobile Tests

Mobile app tests are located in `/mobile/__tests__/` and use Jest:

```bash
cd mobile
npm test
```

## API Tests (Postman)

API tests collection is available in `/postman/` directory.

Import into Postman and run:
```bash
newman run postman/travaux-pro-api-tests.json
```
