# Test Fixtures

Place test data and mock objects here.

## Purpose

Fixtures provide reusable test data:
- Sample customer records
- Mock payment responses
- Test voucher codes
- Dummy configurations

## Structure

```
fixtures/
├── CustomerFixture.php
├── PaymentFixture.php
├── VoucherFixture.php
└── data/
    ├── customers.json
    ├── payments.json
    └── vouchers.json
```

## Example

```php
<?php

namespace Tests\Fixtures;

class CustomerFixture
{
    public static function create(array $overrides = [])
    {
        $defaults = [
            'name' => 'Test Customer',
            'email' => 'test@example.com',
            'phone' => '+1234567890'
        ];
        
        return array_merge($defaults, $overrides);
    }
}
```
