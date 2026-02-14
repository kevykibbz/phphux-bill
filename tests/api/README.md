# API Tests

Place API endpoint tests here.

## Purpose

Test all API endpoints for:
- Request/response formats
- Authentication
- Error handling
- Edge cases

## Example

```php
<?php

namespace Tests\Api;

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;

class WebhookTest extends TestCase
{
    protected $client;
    
    protected function setUp(): void
    {
        $this->client = new Client([
            'base_uri' => 'http://localhost/phpnuxbill',
            'http_errors' => false
        ]);
    }
    
    public function testWebhookEndpoint()
    {
        $response = $this->client->post('/api/webhook.php', [
            'json' => ['test' => 'data']
        ]);
        
        $this->assertEquals(200, $response->getStatusCode());
    }
}
```
