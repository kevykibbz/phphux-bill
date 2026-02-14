# Test Helpers

Place test helper functions and utilities here.

## Purpose

Shared testing utilities:
- Database helpers
- Authentication helpers
- Mock builders
- Common assertions

## Example

```php
<?php

namespace Tests\Helpers;

class DatabaseHelper
{
    public static function createTestDatabase()
    {
        // Create and seed test database
    }
    
    public static function cleanupTestDatabase()
    {
        // Clean up after tests
    }
}

class AuthHelper
{
    public static function loginAsAdmin()
    {
        // Authenticate as admin for tests
    }
    
    public static function loginAsCustomer($customerId)
    {
        // Authenticate as customer for tests
    }
}
```
