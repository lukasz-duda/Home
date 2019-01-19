<?php
namespace Assistant\Shared\Application;

require __DIR__ . '/../../../vendor/autoload.php';

class Response {

    public $value;
    public $error;
    public $success;

    public function fromResult($result) {
        $response = new Response();
        $response->error = $result->error();
        $response->success = $result->success();
        return $response;
    }
    
    public function ok() {
        $this->success = true;
    }
}
