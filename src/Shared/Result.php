<?php
namespace Assistant\Shared;

class Result {

    private $value;
    private $error;
    private $success;

    public function ok($value) {
        $this->value = $value;
        $this->success = true;
    }

    public function fail($message) {
        $this->error = $message;
        $this->success = false;
    }

    public function value() {
        return $this->value;
    }

    public function error() {
        return $this->error;
    }

    public function success() {
        return $this->success;
    }

    public function failure() {
        return !$this->success;
    }

}
