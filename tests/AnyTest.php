<?php
declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;

class AnyTest extends TestCase {
	public function testAbc() {
		$client = new GuzzleHttp\Client(['base_uri' => 'https://foo.com/api/']);
		$response = $client->get('http://httpbin.org/get');
		echo $response->getBody();
	}
}


