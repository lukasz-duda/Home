<?php
namespace Assistant\CatFeeding;

class Cat {

    private $name;

    private function __construct($name) {
        $this->name = $name;
    }

    public static function create($name) {
        $result = new \Assistant\Shared\Result();

        if(!$name instanceof \Assistant\Shared\Name) {
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
