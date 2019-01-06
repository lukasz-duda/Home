<?php

class CommandResponse {
    public $information = 'Połączenie z API zostało nawiązane poprawnie.';
}

header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');

echo json_encode(new CommandResponse());
