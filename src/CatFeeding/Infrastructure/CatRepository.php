<?php
namespace Assistant\CatFeeding\Infrastructure;

class CatRepository {
    
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    public function save($cat) {
        $statement = $this->pdo->prepare('INSERT INTO cats (name) VALUES (?)');
        $statement->execute(['a']);
    }
    
}