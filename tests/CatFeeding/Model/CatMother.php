<?php
namespace Assistant\Tests\CatFeeding\Model;

require __DIR__ . '/../../../vendor/autoload.php';

use Assistant\Shared\Name;
use Assistant\CatFeeding\Cat;
use Assistant\Tests\Shared\Model\NameMother;

class CatMother {
    
    public static function createCat() {
        $name = NameMother::createName();
        $catResult = Cat::create($name);
        return $catResult->value();
    }
    
}