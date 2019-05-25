<?php

require __DIR__ . '/../../../vendor/autoload.php';

use Assistant\Shared\Name;
use Assistant\Shared\Application\Response;
use Assistant\CatFeeding\Model\Cat;
use Assistant\CatFeeding\Infrastructure\CatRepository;

$request = json_decode(file_get_contents('php://input'), true);
$catName = filter_var($request['catName'], FILTER_SANITIZE_STRING);

header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');

$nameResult = Name::create($catName);
if ($nameResult->failure()) {
    $response = Response::fromResult($nameResult);
    echo json_encode($response);
    return;
}

$catResult = Cat::create($nameResult->value());

$dsn = 'mysql:dbname=assistant;host=localhost';
$user = 'assistant';
$password = 'assistant';
$pdo = new PDO($dsn, $user, $password);
$repository = new CatRepository($pdo);

$saveResult = $repository->save($catResult->value());

$response = new Response();
$response->ok();
echo json_encode($response);
