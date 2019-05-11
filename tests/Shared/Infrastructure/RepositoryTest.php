<?php

namespace Assistant\Tests\Shared\Infrastructure;

require __DIR__ . '/../../../vendor/autoload.php';

use Assistant\Tests\Shared\Test;

abstract class RepositoryTest extends Test
{

    protected $pdo;

    public static function setUpBeforeClass()
    {
        $executeSqlCmd = 'mysql --login-path=local --database=assistant_test < ';
        $createDatabaseSql = __DIR__ . '/../../../src/CatFeeding/Infrastructure/database.sql';
        exec($executeSqlCmd . $createDatabaseSql);
    }

    public function setUp()
    {
        $dsn = 'mysql:dbname=assistant_test;host=localhost';
        $user = 'assistant';
        $password = 'assistant';
        $this->pdo = new \PDO($dsn, $user, $password);
        $this->pdo->beginTransaction();
    }

    public function tearDown()
    {
        $this->pdo->rollBack();
    }

}

