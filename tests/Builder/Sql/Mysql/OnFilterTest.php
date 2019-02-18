<?php

declare(strict_types=1);

namespace test\Builder\Sql\MySql;

use PHPUnit\Framework\TestCase;
use Redstraw\Hooch\Builder\Sql\Mysql\OnFilter;
use Redstraw\Hooch\Query\Common\Operator\Comparison;
use Redstraw\Hooch\Query\Common\Operator\Logical;
use Redstraw\Hooch\Query\Common\Operator\Operator;
use Redstraw\Hooch\Query\Sql\Accent;
use Redstraw\Hooch\Query\Sql\Query;
use Redstraw\Hooch\Query\Sql\Sql;
use Redstraw\Hooch\Query\Sql\Statement\FilterInterface;
use Redstraw\Hooch\Query\Sql\Statement\OnFilterInterface;

/**
 * Class OnFilterTest
 * @package test\Builder\Sql\MySql
 */
class OnFilterTest extends TestCase
{
    /**
     * @var Query
     */
    private $query;

    /**
     * @var Operator
     */
    private $operator;

    /**
     * @var OnFilterInterface
     */
    private $onFilter;

    public function setUp()
    {
        $this->query = new Query(
            new Sql(),
            new Accent()
        );
        $this->query->accent()->setSymbol('`');
        $this->operator = new Operator(
            new Comparison(
                new \Redstraw\Hooch\Query\Common\Operator\Comparison\Param(new Sql()),
                new \Redstraw\Hooch\Query\Common\Operator\Comparison\SubQuery(new Sql()),
                new \Redstraw\Hooch\Query\Common\Operator\Comparison\Column(new Sql(), $this->query->accent())
            ),
            new Logical(
                new \Redstraw\Hooch\Query\Common\Operator\Logical\Param(new Sql()),
                new \Redstraw\Hooch\Query\Common\Operator\Logical\SubQuery(new Sql()),
                new \Redstraw\Hooch\Query\Common\Operator\Logical\Column(new Sql(), $this->query->accent())
            )
        );
        $this->onFilter = new OnFilter($this->query, $this->operator);
    }

    public function tearDown()
    {
        $this->query = null;
        $this->operator = null;
        $this->onFilter = null;
    }

    public function testOn()
    {
        $query = $this->onFilter->on('col_a', $this->operator->comparison()->column()->equalTo('col_b'))->build();

        $this->assertEquals("ON `col_a` = `col_b`", trim($query->string()));
        $this->assertEquals([], $query->parameters());
    }

    public function testOnWithAlias()
    {
        $query = $this->onFilter->on('t.col_a', $this->operator->comparison()->column()->equalTo('col_b'))->build();

        $this->assertEquals("ON `t`.`col_a` = `col_b`", trim($query->string()));
        $this->assertEquals([], $query->parameters());
    }

    public function testOnAndOn()
    {
        $query = $this->onFilter->on('col_a', $this->operator->comparison()->column()->equalTo('col_b'))
            ->on('col_c', $this->operator->comparison()->column()->equalTo('col_d'))
            ->build();

        $this->assertEquals("ON `col_a` = `col_b` AND `col_c` = `col_d`", trim($query->string()));
        $this->assertEquals([], $query->parameters());
    }

    public function testOnOrOn()
    {
        $query = $this->onFilter->on('col_a', $this->operator->comparison()->column()->equalTo('col_b'))
            ->orOn('col_c', $this->operator->comparison()->column()->equalTo('col_d'))
            ->build();

        $this->assertEquals("ON `col_a` = `col_b` OR `col_c` = `col_d`", trim($query->string()));
        $this->assertEquals([], $query->parameters());
    }

    public function testOnAndWhere()
    {
        $query = $this->onFilter->on('col_a', $this->operator->comparison()->param()->equalTo('some_value'))
            ->where('col_b',$this->operator->comparison()->param()->equalTo('another_value'))->build();

        $this->assertEquals("ON `col_a` =? AND `col_b` =?", trim($query->string()));
        $this->assertEquals(['some_value','another_value'], $query->parameters());
    }

    public function testOnOrWhere()
    {
        $query = $this->onFilter->on('col_a', $this->operator->comparison()->param()->equalTo('some_value'))
            ->orWhere('col_b',$this->operator->comparison()->param()->equalTo('another_value'))->build();

        $this->assertEquals("ON `col_a` =? OR `col_b` =?", trim($query->string()));
        $this->assertEquals(['some_value','another_value'], $query->parameters());
    }

    public function testOnAndNestedWhere()
    {
        $operator = $this->operator;
        $query = $this->onFilter->on('col_a', $this->operator->comparison()->param()->equalTo('some_value'))
            ->nestedWhere(function() use ($operator){
                /** @var FilterInterface $this */
                $this->where('tt.col_b', $operator->comparison()->param()->equalTo('another_value'));
            })->build();

        $this->assertEquals("ON `col_a` =? AND ( `tt`.`col_b` =? )", trim($query->string()));
        $this->assertEquals(['some_value','another_value'], $query->parameters());
    }
}
