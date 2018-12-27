<?php

class CommandResponse {
	public $information = 'Połączenie z API zostało nawiązane poprawnie.';
}

echo json_encode(new CommandResponse());
