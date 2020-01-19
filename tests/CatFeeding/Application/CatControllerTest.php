<?php /** @noinspection PhpParamsInspection */

namespace Assistant\Tests\CatFeeding\Application;

require __DIR__ . '/../../../vendor/autoload.php';

use Assistant\Tests\Shared\Test;
use Assistant\Tests\Shared\Model\Names;
use GuzzleHttp\Client as HttpClient;
use PDO;

class CatController extends Test
{

    function testAddCatWithEmptyNameReturnsWarning()
    {
        $client = new HttpClient();

        $response = $client->request('POST', 'http://localhost/HomeAssistant/src/CatFeeding/Application/CatController.php', [
            'json' => [
                'catName' => Names::INVALID
            ]
        ]);

        $this->assertEquals(200, $response->getStatusCode());
        $json = $response->getBody()->getContents();
        $this->assertJsonStringEqualsJsonString(file_get_contents(__DIR__ . '/empty-cat-name.json'), $json);
    }

    function testAddCatWithCorrectNameReturnsSuccess()
    {
        $client = new HttpClient();

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

    function testAddCatWithCorrectNameAddsCat()
    {
        $client = new HttpClient();

        $client->request('POST', 'http://localhost/HomeAssistant/src/CatFeeding/Application/CatController.php', [
            'json' => [
                'catName' => Names::VALID
            ]
        ]);

        $dsn = 'mysql:dbname=home;host=localhost';
        $user = 'home';
        $password = 'home';
        $pdo = new PDO($dsn, $user, $password);
        $testName = Names::VALID;

        $savedCats = $pdo->query("SELECT * FROM cats WHERE name = '$testName'");
        $this->assertEquals(1, $savedCats->rowCount());
    }

    function tearDown()
    {
        $dsn = 'mysql:dbname=home;host=localhost';
        $user = 'home';
        $password = 'home';
        $pdo = new PDO($dsn, $user, $password);
        $testName = Names::VALID;
        $pdo->exec("DELETE FROM cats WHERE name = '$testName'");
    }

}
