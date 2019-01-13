<?php
namespace Assistant\Tests\CatFeeding;

require __DIR__ . '/../../vendor/autoload.php';

use Assistant\Tests\Shared\Test;
use Assistant\Shared\Name;
use Assistant\CatFeeding\Cat;

class CatTest extends Test {

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

        $this->assertFailure($result);
    }
    
    public function testCantUseStringInsteadOfName() {
        $result = Cat::create('any string');

        $this->assertFailure($result);
    }
}
