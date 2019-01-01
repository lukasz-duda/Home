<?php
namespace Assistant\Shared;

require __DIR__ . '/../../vendor/autoload.php';

class NameTest extends Test {

    public function testEmptyNameIsAFailure() {
        $result = Name::create('');
        
        $this->assertFailure($result);
    }
}