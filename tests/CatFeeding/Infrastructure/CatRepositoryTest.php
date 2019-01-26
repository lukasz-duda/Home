<?php
namespace Assistant\Tests\CatFeeding\Infrastructure;

require __DIR__ . '/../../../vendor/autoload.php';

use Assistant\Tests\Shared\Infrastructure\RepositoryTest;
use Assistant\Tests\Shared\Infrastructure\ErrorPdo;
use Assistant\Tests\CatFeeding\Model\CatMother;
use Assistant\Tests\Shared\Model\NameMother;
use Assistant\Shared\Name;
use Assistant\CatFeeding\Cat;
use Assistant\CatFeeding\Infrastructure\CatRepository;

class CatRepositoryTest extends RepositoryTest {
    
    private $sut;
    private $cat;
    
    public function setUp() {
        parent::setUp();
        $this->sut = new CatRepository($this->pdo);

        $this->cat = CatMother::createCat();
    }
    
    public function testGetByName() {
        $catName = $this->cat->name()->value();
        $this->sut->save($this->cat);

        $returnedCat = $this->sut->getByName($catName);
        
        $this->assertEquals($this->cat->name(), $returnedCat->name());
    }

    public function testGetByNameWithDatabaseErrorReturnsFailure() {
        $this->setUpDatabaseError();

        $result = $this->sut->getByName('');

        $this->assertFailure($result);
    }

    private function setUpDatabaseError() {
        $errorPdo = new ErrorPdo();
        $this->sut = new CatRepository($errorPdo);
    }

    public function testSaveWithDatabaseErrorReturnsFailure() {
        $this->setUpDatabaseError();

        $result = $this->sut->save($this->cat);

        $this->assertFailure($result);
    }

}
