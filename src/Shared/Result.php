<?php
namespace Assistant\Shared;

class Result {

    private $value;

    public function ok($value) {
        $this->value = $value;
    }

    public function value() {
        return $this->value;
    }

}
