<?php
namespace Assistant\CatFeeding;

class Cat {

    private $name;

    private function __construct($name) {
        $this->name = $name;
    }

    public static function create($name) {
        return new Cat($name);
    }

    public function name() {
        return $this->name;
    }
}
