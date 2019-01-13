<?php
namespace Assistant\Tests\CatFeeding;

require __DIR__ . '/../../vendor/autoload.php';

use Assistant\Tests\Shared\Test;
use Goutte\Client;

class CatController extends Test {
    
    function testCanCall() {
        $client = new \GuzzleHttp\Client();
        
        $response = $client->request('GET', 'http://localhost/assistant/src/CatFeeding/CatController.php');
    }
    
}