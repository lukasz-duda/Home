<?php
include __DIR__ . '/../../Configuration.php';

$pdo = new PDO($dsn, $user, $password);
$pdo->query('SET NAMES utf8');
$pdo->query('SET CHARACTER_SET utf8_unicode_ci');

function get($query, $params)
{
    global $pdo;
    $statement = $pdo->prepare($query);
    $statement->execute($params);
    $row = $statement->fetch();
    return $row;
}

function getAll($query, $params)
{
    global $pdo;
    $statement = $pdo->prepare($query);
    $statement->execute($params);
    $rows = $statement->fetchAll();
    return $rows;
}

?>