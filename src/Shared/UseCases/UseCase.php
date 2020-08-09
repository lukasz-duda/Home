<?php
include '../../Shared/Views/View.php';

function notValidString($text)
{
    return !is_string($text) || trim($text) == '';
}

function notValidId($id)
{
    return $id <= 0;
}

function notValidValue($value)
{
    return $value <= 0;
}

function notValidJson($text)
{
    return json_decode($text) === null;
}

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    showFinalWarning('Wykonaj polecenie ponownie na formularzu.');
}