<?php

declare(strict_types=1);

namespace QueryMule\Query\Common\Sql;


use QueryMule\Query\Exception\SqlException;
use QueryMule\Query\Repository\RepositoryInterface;
use QueryMule\Query\Sql\Operator\OperatorInterface;
use QueryMule\Query\Sql\Sql;
use QueryMule\Query\Sql\Statement\SelectInterface;

/**
 * Trait HasRightJoin
 * @package QueryMule\Query\Common\Sql
 */
trait HasRightJoin
{
    /**
     * @param RepositoryInterface $table
     * @param $column
     * @param OperatorInterface|null $operator
     * @return SelectInterface
     * @throws SqlException
     */
    public function rightJoin(RepositoryInterface $table, $column, ?OperatorInterface $operator = null): SelectInterface
    {
        if($this instanceof SelectInterface) {
            $this->join(Sql::JOIN_RIGHT, $table)->onFilter()->on($column, $operator);

            return $this;
        }else {
            throw new SqlException(sprintf("Must invoke SelectInterface in: %s.", get_class($this)));
        }
    }
}