<?php
declare(strict_types=1);

namespace test\Builder\Sql\MySql;

use PHPUnit\Framework\TestCase;
use Redstraw\Hooch\Builder\Sql\MySql\Filter;
use Redstraw\Hooch\Builder\Sql\Mysql\Insert;
use Redstraw\Hooch\Builder\Sql\Mysql\OnFilter;
use Redstraw\Hooch\Query\Repository\RepositoryInterface;
use Redstraw\Hooch\Query\Sql\Accent;
use Redstraw\Hooch\Query\Sql\Operator\Comparison;
use Redstraw\Hooch\Query\Sql\Operator\Logical;
use Redstraw\Hooch\Query\Sql\Operator\Operator;
use Redstraw\Hooch\Query\Sql\Query;
use Redstraw\Hooch\Query\Sql\Sql;
use Redstraw\Hooch\Query\Sql\Statement\FilterInterface;
use Redstraw\Hooch\Query\Sql\Statement\InsertInterface;
use Redstraw\Hooch\Query\Sql\Statement\OnFilterInterface;

/**
 * Class InsertTest
 * @package test\Builder\Sql\MySql
 */
class InsertTest extends TestCase
{
    /**
     * @var Query
     */
    private $query;

    /**
     * @var InsertInterface
     */
    private $insert;

    public function setUp()
    {
        $this->query = new Query(new Sql(), new Logical(), new Accent());
        $this->query->accent()->setSymbol('`');

        $this->insert = new Insert($this->query);
    }

    public function tearDown()
    {
        $this->query = null;
        $this->insert = null;
    }

    public function testInsert()
    {
        $table = $this->createMock(RepositoryInterface::class);
        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));

        $query = $this->insert->into($table)->insert(["key"=>"value", "another_key"=>"another_value"])->build();

        $this->assertEquals("INSERT INTO `some_table_name` ( `key`,`another_key` ) VALUES ( ?,? )", trim($query->string()));
        $this->assertEquals(['value','another_value'], $query->parameters());
    }

    public function testInsertOnDuplicateKeyUpdate()
    {
        $table = $this->createMock(RepositoryInterface::class);
        $table->expects($this->any())->method('getName')->will($this->returnValue('some_table_name'));

        $query = $this->insert->into($table)
            ->insert(["key"=>"value"])
            ->onDuplicateKeyUpdate(["another_key"=>"another_value"])
            ->build();

        $this->assertEquals("INSERT INTO `some_table_name` ( `key` ) VALUES ( ? ) ON DUPLICATE KEY UPDATE `another_key` =?", trim($query->string()));
        $this->assertEquals(['value','another_value'], $query->parameters());
    }
}