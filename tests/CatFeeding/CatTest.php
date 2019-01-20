<?php
namespace Assistant\Tests\CatFeeding;

require __DIR__ . '/../../vendor/autoload.php';

use Assistant\Tests\Shared\Test;
use Assistant\Shared\Name;
use Assistant\CatFeeding\Cat;
use Assistant\Tests\Shared\Model\NameMother;

class CatTest extends Test {

    public function testCanCreateNamedCat() {
        $name = NameMother::createName();

        $result = Cat::create($name);

        $cat = $result->value();
        $this->assertNotNull($cat);
        $this->assertEquals($name, $cat->name());
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
