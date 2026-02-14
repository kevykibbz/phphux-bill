# Integration Tests

Place integration tests for testing component interactions here.

## Purpose

Integration tests verify that multiple components work together correctly:
- Database interactions
- API integrations
- Payment workflows
- Authentication flows

## Example

```php
<?php

namespace Tests\Integration;

use PHPUnit\Framework\TestCase;

class PaymentFlowTest extends TestCase
{
    protected function setUp(): void
    {
        // Setup test database
        parent::setUp();
    }
    
    public function testCompletePaymentFlow()
    {
        // Test complete workflow
    }
    
    protected function tearDown(): void
    {
        // Cleanup
        parent::tearDown();
    }
}
```
