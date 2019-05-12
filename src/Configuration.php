<?php

$dsn = 'mysql:dbname=assistant;host=localhost';
$user = 'assistant';
$password = 'assistant';

function newPdo()
{
    global $dsn, $user, $password;
    $pdo = new PDO($dsn, $user, $password);
    $pdo->query('SET NAMES utf8');
    $pdo->query('SET CHARACTER_SET utf8_unicode_ci');
    return $pdo;
}