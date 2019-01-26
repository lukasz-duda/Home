<?php
namespace Assistant\CatFeeding\Infrastructure;

require __DIR__ . '/../../../vendor/autoload.php';

use Assistant\Shared\Name;
use Assistant\Shared\Result;
use Assistant\CatFeeding\Model\Cat;

class CatRepository {
    
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    public function save($cat) {
        try {
            $statement = $this->pdo->prepare('INSERT INTO cats (name) VALUES (?)');
            $statement->execute([$cat->name()->value()]);
        } catch (\Exception $e) {
            return $this->databaseFailure(); 
        }
    }

    private function databaseFailure() {
        $result = new Result();
        $result->fail('Błąd podczas komunikacji z bazą danych.');
        return $result;
    }
    
    public function getByName($name) {
        try {
            $statement = $this->pdo->prepare('SELECT * FROM cats WHERE name = ?');
            $statement->execute([$name]);
            $row = $statement->fetch();
            $nameResult = Name::create($row['name']);
            $catResult = Cat::create($nameResult->value());
            return $catResult->value();
        } catch (\Exception $e) {
            return $this->databaseFailure(); 
        }
    }
}
