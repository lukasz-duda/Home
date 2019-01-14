<?php
namespace Assistant\Tests\CatFeeding;

require __DIR__ . '/../../../vendor/autoload.php';

use Assistant\Tests\Shared\Test;
use Goutte\Client;

class CatController extends Test {
    
    function testCanCall() {
        $client = new \GuzzleHttp\Client();
        
        $response = $client->request('POST', 'http://localhost/assistant/src/CatFeeding/Application/CatController.php', [
            'json' => [
                'catName' => ''
            ]
        ]);
        
        $this->assertEquals(200, $response->getStatusCode());
        $json = $response->getBody()->getContents();
        $this->assertJsonStringEqualsJsonString('{}', $json);
    }
    
}