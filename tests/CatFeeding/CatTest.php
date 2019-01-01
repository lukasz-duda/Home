<?php
namespace Assistant\CatFeeding;

require __DIR__ . '/../../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use Assistant\Shared\Name;

class CatTest extends TestCase {

    public function testCanCreateNamedCat() {
        $expectedName = 'Mruczek';
        $nameResult = Name::create($expectedName);

        $result = Cat::create($nameResult->value());

        $cat = $result->value();
        $this->assertNotNull($cat);
        $this->assertEquals($expectedName, $cat->name()->value());
    }

    public function testUnnamedCatIsAFailure() {
        $result = Cat::create(null);

        $cat = $result->value();
        $this->assertNull($cat);
        $this->assertTrue($result->failure());
        $this->assertFalse($result->success());
        $this->assertEquals('Kot musi mieć imię.', $result->error());
    }
}
