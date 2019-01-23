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
    
    public function setUp() {
        parent::setUp();
        $this->sut = new CatRepository($this->pdo);
    }
    
    public function testGetByName() {
        $cat = CatMother::createCat();
        $catName = $cat->name()->value();
        $this->sut->save($cat);

        $returnedCat = $this->sut->getByName($catName);
        
        $this->assertEquals($cat->name(), $returnedCat->name());
    }

    public function testGetByNameWithDatabaseErrorReturnsFailure() {
        $errorPdo = new ErrorPdo();
        $this->sut = new CatRepository($errorPdo);

        $result = $this->sut->getByName('');

        $this->assertTrue($result->failure());
    }

    public function testSaveWithDatabaseErrorReturnsFailure() {
        $errorPdo = new ErrorPdo();
        $this->sut = new CatRepository($errorPdo);

        $cat = CatMother::createCat();

        $result = $this->sut->save($cat);

        $this->assertTrue($result->failure());
    }

}
