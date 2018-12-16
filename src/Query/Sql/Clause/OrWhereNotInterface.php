<?php

namespace QueryMule\Query\Sql\Clause;


use QueryMule\Query\Sql\Operator\OperatorInterface;

/***
 * Interface OrWhereInterface
 * @package QueryMule\Query\Sql\Clause
 */
interface OrWhereNotInterface
{
    /**
     * @param $column
     * @param OperatorInterface $operator
     * @return mixed
     */
    public function orWhereNot($column, OperatorInterface $operator);
}