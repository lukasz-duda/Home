<?php
namespace Assistant\Tests\CatFeeding\Application;

require __DIR__ . '/../../../vendor/autoload.php';

use Goutte\Client;
use Assistant\Tests\Shared\Test;
use Assistant\Tests\Shared\Model\Names;

class CatController extends Test {
    
    function testAddCatWithEmptyNameReturnsWarning() {
        $client = new \GuzzleHttp\Client();
        
        $response = $client->request('POST', 'http://localhost/HomeAssistant/src/CatFeeding/Application/CatController.php', [
            'json' => [
                'catName' => Names::INVALID
            ]
        ]);
        
        $this->assertEquals(200, $response->getStatusCode());
        $json = $response->getBody()->getContents();
        $this->assertJsonStringEqualsJsonString(
            file_get_contents(__DIR__ . '/empty-cat-name.json'), $json);
    }
    
    function testAddCatWithCorrectNameReturnsSuccess() {
        $client = new \GuzzleHttp\Client();
        
        $response = $client->request('POST', 'http://localhost/HomeAssistant/src/CatFeeding/Application/CatController.php', [
            'json' => [
                'catName' => Names::VALID
            ]
        ]);
        
        $this->assertEquals(200, $response->getStatusCode());
        $json = $response->getBody()->getContents();
        $this->assertJsonStringEqualsJsonString(
            file_get_contents(__DIR__ . '/add-cat-success.json'), $json);
    }
    
    function testAddCatWithCorrectNameAddsCat() {
        $client = new \GuzzleHttp\Client();
        
        $response = $client->request('POST', 'http://localhost/HomeAssistant/src/CatFeeding/Application/CatController.php', [
            'json' => [
                'catName' => Names::VALID
            ]
        ]);

        $dsn = 'mysql:dbname=assistant;host=localhost';
        $user = 'assistant';
        $password = 'assistant';
        $pdo = new \PDO($dsn, $user, $password);
        $testName = Names::VALID;
        
        $savedCats = $pdo->query("SELECT * FROM cats WHERE name = '$testName'");
        $this->assertEquals(1, $savedCats->rowCount());
    }
    
    function tearDown() {
        $dsn = 'mysql:dbname=assistant;host=localhost';
        $user = 'assistant';
        $password = 'assistant';
        $pdo = new \PDO($dsn, $user, $password);
        $testName = Names::VALID;
        $pdo->exec("DELETE FROM cats WHERE name = '$testName'");
    }
    
}
