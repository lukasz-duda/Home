<?php
namespace Assistant\CatFeeding\Infrastructure;

require __DIR__ . '/../../../vendor/autoload.php';

use Assistant\Shared\Test;
use Assistant\Shared\Name;
use Assistant\CatFeeding\Cat;

class CatRepositoryTest extends Test {
    
    private $sut;
    private $pdo;
    
    public function setUp() {
        $dsn = 'mysql:dbname=cat_feeding;host=localhost';
        $user = 'root';
        $password = null;
        $this->pdo = new \PDO($dsn, $user, $password);
        $this->pdo->beginTransaction();
        $this->sut = new CatRepository($this->pdo);
    }
    
    public function testAssignsId() {
        $name = Name::create('CatName')->value();
        $cat = Cat::create($name)->value();
        
        $this->sut->save($cat);
        
        $this->assertNotEmpty($this->pdo->lastInsertId());
    }
    
    public function tearDown() {
        $this->pdo->rollBack();
    }
    
}