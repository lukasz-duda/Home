<?php

class CommandResponse {
	public $information = 'Połączenie z API zostało nawiązane poprawnie.';
}

header('Content-Type: application/json');

echo json_encode(new CommandResponse());
