<?php
namespace Assistant\Shared;

require __DIR__ . '/../../vendor/autoload.php';

use PHPUnit\Framework\TestCase;

class Test extends TestCase {
    
    public function assertFailure($result) {
        $this->assertTrue($result->failure());
        $this->assertNotEmpty($result->error());
        $this->assertFalse($result->success());
        $this->assertNull($result->value());
    }
}