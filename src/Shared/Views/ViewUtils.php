<?php
date_default_timezone_set('Europe/Warsaw');

$pdo = newPdo();

function newPdo()
{
    global $dsn, $user, $password;
    $pdo = new PDO($dsn, $user, $password);
    $pdo->query('SET NAMES utf8mb4');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
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
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

function notValidId($id)
{
    return $id <= 0;
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

function showFinalWarning($text)
{
    showWarning($text);
    include '../../Shared/Views/Footer.php';
    exit();
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

function showDateTime($value)
{
    return date('Y-m-d H:i:s', $value);
}

$now = time();

function now()
{
    global $now;
    return showDateTime($now);
}

function today()
{
    global $now;
    return showDate($now);
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

$start = microtime(true);
$duration = 0.0;

function tooLong()
{
    global $start, $duration;
    $duration = microtime(true) - $start;
    $limit = 0.5;
    return $duration > $limit;
}

function timeSpent()
{
    global $duration;
    return sprintf('%.1f', $duration);
}