<?php
namespace Assistant\Tests\CatFeeding;

require __DIR__ . '/../../../vendor/autoload.php';

use Assistant\Tests\Shared\Test;
use Goutte\Client;

class CatController extends Test {
    
    function testAddCatWithEmptyNameReturnsWarning() {
        $client = new \GuzzleHttp\Client();
        
        $response = $client->request('POST', 'http://localhost/assistant/src/CatFeeding/Application/CatController.php', [
            'json' => [
                'catName' => ''
            ]
        ]);
        
        $this->assertEquals(200, $response->getStatusCode());
        $json = $response->getBody()->getContents();
        $this->assertJsonStringEqualsJsonString(
            file_get_contents(__DIR__ . '\empty-cat-name.json'), $json);
    }
    
    function testAddCatWithCorrectNameReturnsSuccess() {
        $client = new \GuzzleHttp\Client();
        
        $response = $client->request('POST', 'http://localhost/assistant/src/CatFeeding/Application/CatController.php', [
            'json' => [
                'catName' => 'TestCat1'
            ]
        ]);
        
        $this->assertEquals(200, $response->getStatusCode());
        $json = $response->getBody()->getContents();
        $this->assertJsonStringEqualsJsonString(
            file_get_contents(__DIR__ . '\add-cat-success.json'), $json);
    }
    
    function testAddCatWithCorrectNameAddsCat() {
        $client = new \GuzzleHttp\Client();
        
        $response = $client->request('POST', 'http://localhost/assistant/src/CatFeeding/Application/CatController.php', [
            'json' => [
                'catName' => 'TestCat1'
            ]
        ]);

        $dsn = 'mysql:dbname=assistant;host=localhost';
        $user = 'assistant';
        $password = 'assistant';
        $pdo = new \PDO($dsn, $user, $password);
        
        $savedCats = $pdo->query("SELECT COUNT(1) FROM cats WHERE name = 'TestCat1'");
        $this->assertEquals(1, $savedCats->rowCount());
    }
    
    function tearDown() {
        $dsn = 'mysql:dbname=assistant;host=localhost';
        $user = 'assistant';
        $password = 'assistant';
        $pdo = new \PDO($dsn, $user, $password);
        
        $pdo->exec("DELETE FROM cats WHERE name = 'TestCat1'");
    }
    
}