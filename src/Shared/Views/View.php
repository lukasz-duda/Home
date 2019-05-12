<?php
include __DIR__ . '/../../Configuration.php';

$pdo = newPdo();

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