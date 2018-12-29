<?php
namespace Assistant\CatFeeding;

require __DIR__ . '/../../vendor/autoload.php';

use PHPUnit\Framework\TestCase;

class CatTest extends TestCase {

    public function testCanCreate() {
        new Cat();
    }

}
