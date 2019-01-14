<?php

require __DIR__ . '/../../../vendor/autoload.php';

$request = json_decode(file_get_contents('php://input'), true);
$catName = filter_var($request['catName'], FILTER_SANITIZE_STRING);

header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');

class Response {}

echo json_encode(new Response());