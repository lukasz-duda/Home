<?php
include '../../Shared/Views/View.php';

function notValidString($text)
{
    return !is_string($text) || trim($text) == '';
}

function notValidValue($value)
{
    return $value <= 0;
}

function notValidJson($text)
{
    return json_decode($text) === null;
}

function notValidDate($text)
{
    if (notValidString($text)) {
        return true;
    }

    return DateTime::createFromFormat('Y-m-d', $text) === false;
}

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    showFinalWarning('Wykonaj polecenie ponownie na formularzu.');
}