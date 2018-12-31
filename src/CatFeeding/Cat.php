<?php
namespace Assistant\CatFeeding;

class Cat {

    private $name;

    public function __construct($name) {
        $this->name = $name;
    }

    public function name() {
        return $this->name;
    }
}
