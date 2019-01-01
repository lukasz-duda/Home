<?php
namespace Assistant\Shared;

class Name {

    private $value;

    private function __construct($name) {
        $this->value = $name;
    }

    public static function create($name) {
        $result = new Result();
        
        if($name == '') {
            $result->fail('Nazwa nie może być pusta.');
            return $result;
        }
        
        $newName = new Name($name);
        $result->ok($newName);
        return $result;
    }

    public function value() {
        return $this->value;
    }

}
