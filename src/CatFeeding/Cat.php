<?php
namespace Assistant\CatFeeding;

use Assistant\Shared\Result;
use Assistant\Shared\Name;

class Cat {

    private $name;

    private function __construct($name) {
        $this->name = $name;
    }

    public static function create($name) {
        $result = new Result();

        if(!$name instanceof Name) {
            $result->fail('Kot musi mieć imię.');
            return $result;
        }

        $cat = new Cat($name);
        $result->ok($cat);
        return $result;
    }

    public function name() {
        return $this->name;
    }
}
