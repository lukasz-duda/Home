<?php

namespace Assistant\Tests\Shared\Infrastructure;

require __DIR__ . '/../../../vendor/autoload.php';

use Assistant\Tests\Shared\Test;
use PDO as PDO;

abstract class RepositoryTest extends Test
{
    /**
     * @var PDO
     */
    protected $pdo;

    public function setUp()
    {
        $dsn = 'mysql:dbname=home_test;host=localhost';
        $user = 'home_test';
        $password = 'home_test';
        $this->pdo = new PDO($dsn, $user, $password);
        $this->pdo->beginTransaction();
    }

    public function tearDown()
    {
        $this->pdo->rollBack();
    }

}

