# Unit Tests

Place unit tests for individual functions and classes here.

## Naming Convention

- File: `*Test.php` (e.g., `CustomerTest.php`)
- Class: Must extend `PHPUnit\Framework\TestCase`
- Methods: `test*` prefix (e.g., `testCustomerCreation()`)

## Example

```php
<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    public function testBasicAssertion()
    {
        $this->assertTrue(true);
    }
}
```
