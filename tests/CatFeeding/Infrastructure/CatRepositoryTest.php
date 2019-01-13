<?php
namespace Assistant\Tests\CatFeeding\Infrastructure;

require __DIR__ . '/../../../vendor/autoload.php';

use Assistant\Tests\Shared\Test;
use Assistant\Shared\Name;
use Assistant\CatFeeding\Cat;
use Assistant\CatFeeding\Infrastructure\CatRepository;

class CatRepositoryTest extends Test {
    
    private $sut;
    private $pdo;
    
    public function setUp() {
        $dsn = 'mysql:dbname=assistant_test;host=localhost';
        $user = 'assistant';
        $password = 'assistant';
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
