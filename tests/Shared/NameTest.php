<?php
namespace Assistant\Shared;

require __DIR__ . '/../../vendor/autoload.php';

use PHPUnit\Framework\TestCase;

class NameTest extends TestCase {

    public function testEmptyNameIsAFailure() {
        $result = Name::create('');
        
        $this->assertTrue($result->failure());
        $this->assertNotEmpty($result->error());
    }
}