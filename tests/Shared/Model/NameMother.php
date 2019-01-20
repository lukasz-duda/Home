<?php
namespace Assistant\Tests\Shared\Model;

require __DIR__ . '/../../../vendor/autoload.php';

use Assistant\Shared\Name;
use Assistant\Tests\Shared\Model\Names;

class NameMother {
        
    public static function createName() {
        $nameResult = Name::create(Names::VALID);
        return $nameResult->value();
    }
    
}