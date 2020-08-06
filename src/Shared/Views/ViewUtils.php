<?php
date_default_timezone_set('Europe/Warsaw');

$pdo = newPdo();

function newPdo()
{
    global $dsn, $user, $password;
    $pdo = new PDO($dsn, $user, $password);
    $pdo->query('SET NAMES utf8');
    $pdo->query('SET CHARACTER_SET utf8_unicode_ci');
    return $pdo;
}

function get($query, $params)
{
    global $pdo;
    $statement = $pdo->prepare($query);
    $statement->execute($params);
    return $statement->fetch();
}

function getAll($query, $params)
{
    $statement = pdo()->prepare($query);
    $statement->execute($params);
    return $statement->fetchAll();
}

function showInfo($text)
{
    printf('<div class="alert alert-info" role="alert">%s</div>', $text);
}

function showWarning($text)
{
    printf('<div class="alert alert-warning" role="alert">%s</div>', $text);
}

function showError($text)
{
    printf('<div class="alert alert-danger" role="alert">%s</div>', $text);
}


/**
 * @param $statement PDOStatement
 */
function showStatementError($statement)
{
    printf('<div class="alert alert-danger" role="alert">%s</div>', $statement->errorInfo()[2]);
}

function showInt($value)
{
    return number_format($value, 0, ',', ' ');
}

function showMoney($value)
{
    return showDecimal($value, 2) . ' zł';
}

function showDecimal($value, $decimals)
{
    return number_format($value, $decimals, ',', ' ');
}

function showDate($value)
{
    return date('Y-m-d', $value);
}

$now = time();

function now()
{
    global $now;
    return date('Y-m-d H:m:s', $now);
}

function today()
{
    global $now;
    return date('Y-m-d', $now);
}

function baseUrl()
{
    global $baseUrl;
    return $baseUrl;
}

function pdo()
{
    global $pdo;
    return $pdo;
}

$start = time();

function tooLong()
{
    global $start;
    return time() - $start > 0;
}

function timeSpent()
{
    global $start;
    return time() - $start;
}