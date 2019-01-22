<?php
namespace Assistant\Tests\Shared\Infrastructure;

class ErrorPdo {

    public function prepare() {
        return new ErrorPdoStatement();
    }

}

class ErrorPdoStatement {

    public function execute() {
        throw new \Exception();
    }

}
