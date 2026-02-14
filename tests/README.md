# PHPNuxBill Testing Suite

**Created:** February 14, 2026  
**Purpose:** Centralized testing infrastructure for PHPNuxBill

---

## 📁 Test Structure

```
tests/
├── unit/              # Unit tests for individual functions/classes
├── integration/       # Integration tests for component interaction
├── api/              # API endpoint tests
├── fixtures/         # Test data and mock objects
├── helpers/          # Test helper functions
└── README.md         # This file
```

---

## 🎯 Testing Philosophy

- **Write tests before fixing bugs** - Reproduce the bug first
- **Test one thing at a time** - Each test should verify one specific behavior
- **Make tests independent** - Tests should not depend on each other
- **Use descriptive names** - Test name should explain what it tests
- **Keep tests fast** - Unit tests should run in milliseconds

---

## 🚀 Getting Started

### Prerequisites

```bash
# Install PHPUnit
composer require --dev phpunit/phpunit

# Install testing dependencies
composer install --dev
```

### Running Tests

```bash
# Run all tests
vendor/bin/phpunit

# Run specific test suite
vendor/bin/phpunit tests/unit
vendor/bin/phpunit tests/integration

# Run specific test file
vendor/bin/phpunit tests/unit/PaymentGatewayTest.php

# Run with coverage report
vendor/bin/phpunit --coverage-html coverage/
```

---

## 📝 Writing Tests

### Unit Test Example

```php
<?php
// tests/unit/CustomerTest.php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Customer;

class CustomerTest extends TestCase
{
    public function testCustomerCreation()
    {
        $customer = new Customer([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '+1234567890'
        ]);
        
        $this->assertEquals('John Doe', $customer->getName());
        $this->assertEquals('john@example.com', $customer->getEmail());
        $this->assertTrue($customer->isValid());
    }
    
    public function testInvalidEmail()
    {
        $this->expectException(\InvalidArgumentException::class);
        
        new Customer([
            'name' => 'John Doe',
            'email' => 'invalid-email',
            'phone' => '+1234567890'
        ]);
    }
}
```

### Integration Test Example

```php
<?php
// tests/integration/PaymentFlowTest.php

namespace Tests\Integration;

use PHPUnit\Framework\TestCase;
use Tests\Fixtures\CustomerFixture;
use Tests\Fixtures\PaymentFixture;

class PaymentFlowTest extends TestCase
{
    protected $db;
    
    protected function setUp(): void
    {
        parent::setUp();
        $this->db = $this->createTestDatabase();
    }
    
    public function testSuccessfulPaymentFlow()
    {
        // Arrange
        $customer = CustomerFixture::create();
        $payment = PaymentFixture::createPending($customer);
        
        // Act
        $result = $this->paymentService->process($payment);
        
        // Assert
        $this->assertTrue($result->isSuccessful());
        $this->assertEquals('completed', $payment->getStatus());
        $this->assertTrue($customer->hasActiveSubscription());
    }
    
    protected function tearDown(): void
    {
        $this->cleanupTestDatabase();
        parent::tearDown();
    }
}
```

### API Test Example

```php
<?php
// tests/api/PaystackWebhookTest.php

namespace Tests\Api;

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;

class PaystackWebhookTest extends TestCase
{
    protected $client;
    protected $baseUrl = 'http://localhost/phpnuxbill';
    
    protected function setUp(): void
    {
        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'http_errors' => false
        ]);
    }
    
    public function testWebhookAuthentication()
    {
        $payload = ['event' => 'charge.success'];
        $signature = $this->generateSignature($payload);
        
        $response = $this->client->post('/api/paystack-webhook.php', [
            'json' => $payload,
            'headers' => [
                'X-Paystack-Signature' => $signature
            ]
        ]);
        
        $this->assertEquals(200, $response->getStatusCode());
    }
    
    private function generateSignature($payload)
    {
        $secret = getenv('PAYSTACK_SECRET_TEST');
        return hash_hmac('sha512', json_encode($payload), $secret);
    }
}
```

---

## 🛠️ Test Fixtures

### Creating Fixtures

```php
<?php
// tests/fixtures/CustomerFixture.php

namespace Tests\Fixtures;

use App\Models\Customer;

class CustomerFixture
{
    public static function create(array $overrides = []): Customer
    {
        $defaults = [
            'name' => 'Test Customer',
            'email' => 'test' . time() . '@example.com',
            'phone' => '+1234567890',
            'status' => 'active'
        ];
        
        $attributes = array_merge($defaults, $overrides);
        
        return Customer::create($attributes);
    }
    
    public static function createExpired(): Customer
    {
        return self::create([
            'status' => 'expired',
            'expiry_date' => date('Y-m-d', strtotime('-1 day'))
        ]);
    }
}
```

---

## 🗃️ Test Database

### Setup

```php
<?php
// tests/helpers/DatabaseHelper.php

namespace Tests\Helpers;

class DatabaseHelper
{
    public static function createTestDatabase()
    {
        $config = [
            'host' => '127.0.0.1',
            'database' => 'phpnuxbill_test',
            'username' => 'root',
            'password' => ''
        ];
        
        // Create database if not exists
        $pdo = new \PDO(
            "mysql:host={$config['host']}",
            $config['username'],
            $config['password']
        );
        
        $pdo->exec("CREATE DATABASE IF NOT EXISTS {$config['database']}");
        
        // Run migrations
        self::runMigrations($config);
        
        return $pdo;
    }
    
    public static function cleanupTestDatabase()
    {
        // Truncate all tables
        // or drop database
    }
    
    private static function runMigrations($config)
    {
        // Import schema from install/phpnuxbill.sql
    }
}
```

---

## 📊 Coverage Reports

### Generating Coverage

```bash
# Generate HTML coverage report
vendor/bin/phpunit --coverage-html coverage/

# View in browser
open coverage/index.html
```

### Coverage Goals

- **Critical Functions:** 100% coverage
- **Business Logic:** 80%+ coverage
- **Payment Processing:** 100% coverage
- **Overall Target:** 70%+ coverage

---

## 🎭 Mocking

### Mocking External Services

```php
<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class PaymentGatewayTest extends TestCase
{
    /** @var MockObject */
    private $httpClient;
    
    protected function setUp(): void
    {
        // Mock HTTP client
        $this->httpClient = $this->createMock(HttpClient::class);
    }
    
    public function testPaymentProcessing()
    {
        // Setup mock expectations
        $this->httpClient
            ->expects($this->once())
            ->method('post')
            ->with('/charge')
            ->willReturn(['status' => 'success']);
        
        // Test code that uses the mock
        $gateway = new PaymentGateway($this->httpClient);
        $result = $gateway->charge(100);
        
        $this->assertTrue($result->isSuccessful());
    }
}
```

---

## 🔍 Debugging Tests

### Debugging Tips

```php
// Add debug output (remove before commit!)
echo "Debug: " . print_r($variable, true);
var_dump($variable);

// Use breakpoints with xdebug
xdebug_break();

// Assert what you expect to see
$this->assertEquals(
    $expected,
    $actual,
    "Customer status should be active after payment"
);
```

---

## ⚡ Performance Testing

### Load Testing

```php
<?php
// tests/performance/DatabaseQueryTest.php

namespace Tests\Performance;

use PHPUnit\Framework\TestCase;

class DatabaseQueryTest extends TestCase
{
    public function testCustomerQueryPerformance()
    {
        $start = microtime(true);
        
        // Run query
        $customers = Customer::with('orders')->limit(1000)->get();
        
        $duration = microtime(true) - $start;
        
        // Assert query is fast enough (under 100ms)
        $this->assertLessThan(0.1, $duration, 
            "Customer query took {$duration}s, should be under 0.1s");
    }
}
```

---

## 📋 Testing Checklist

Before pushing code:

- [ ] All tests pass locally
- [ ] New features have tests
- [ ] Bug fixes have regression tests
- [ ] Tests are properly named
- [ ] No test code in production
- [ ] Coverage meets targets
- [ ] No skipped tests without reason
- [ ] Tests are independent

---

## 🐛 Common Issues

### Issue: Database Connection Fails

```php
// Solution: Check test database configuration
// Ensure TEST_DB_NAME is different from production
```

### Issue: Tests Fail Randomly

```php
// Solution: Tests may not be independent
// Check for shared state or race conditions
// Use setUp() and tearDown() properly
```

### Issue: Slow Tests

```php
// Solution: 
// 1. Use mocks for external services
// 2. Use in-memory SQLite for database tests
// 3. Reduce test data size
// 4. Run only necessary assertions
```

---

## 📚 Resources

- [PHPUnit Documentation](https://phpunit.de/documentation.html)
- [Test-Driven Development Guide](https://martinfowler.com/bliki/TestDrivenDevelopment.html)
- [Mocking Best Practices](https://phpunit.de/manual/current/en/test-doubles.html)

---

## 🤝 Contributing Tests

When contributing:

1. Write tests for new features
2. Add regression tests for bugs
3. Follow existing test patterns
4. Document complex test scenarios
5. Keep tests maintainable

---

**Last Updated:** February 14, 2026  
**Maintainer:** Development Team
