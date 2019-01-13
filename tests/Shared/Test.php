<?php
namespace Assistant\Tests\Shared;

require __DIR__ . '/../../vendor/autoload.php';

use PHPUnit\Framework\TestCase;

abstract class Test extends TestCase {
    
    public function assertFailure($result) {
        $this->assertTrue($result->failure());
        $this->assertNotEmpty($result->error());
        $this->assertFalse($result->success());
        $this->assertNull($result->value());
    }
    
    public function assertSuccess($result) {
        $this->assertTrue($result->success());
        $this->assertFalse($result->failure());
        $this->assertNull($result->error());
        $this->assertNotNull($result->value());
    }
}