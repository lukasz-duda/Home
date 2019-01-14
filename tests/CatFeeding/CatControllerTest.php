<?php
namespace Assistant\Tests\CatFeeding;

require __DIR__ . '/../../vendor/autoload.php';

use Assistant\Tests\Shared\Test;
use Goutte\Client;

class CatController extends Test {
    
    function testCanCall() {
        $client = new \GuzzleHttp\Client();
        
        $response = $client->request('POST', 'http://localhost/assistant/src/CatFeeding/CatController.php');
        
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertNotEmpty($response->getBody());
    }
    
}