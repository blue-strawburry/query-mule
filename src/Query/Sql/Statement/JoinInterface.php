<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Sql\Statement;


use Redstraw\Hooch\Query\QueryBuilderInterface;
use Redstraw\Hooch\Query\Repository\RepositoryInterface;
use Redstraw\Hooch\Query\Sql\Operator\OperatorInterface;

/**
 * Interface JoinInterface
 * @package Redstraw\Hooch\Query\Sql\Statement
 */
interface JoinInterface extends QueryBuilderInterface
{
    /**
     * @param string $type
     * @param RepositoryInterface $table
     * @return JoinInterface|SelectInterface|UpdateInterface
     */
    public function join(string $type, RepositoryInterface $table): JoinInterface;

    /**
     * @param RepositoryInterface $table
     * @param $column
     * @param OperatorInterface|null $operator
     * @return JoinInterface|SelectInterface|UpdateInterface
     */
    public function leftJoin(RepositoryInterface $table, $column, ?OperatorInterface $operator = null): JoinInterface;

    /**
     * @param RepositoryInterface $table
     * @param $column
     * @param OperatorInterface|null $operator
     * @return JoinInterface|SelectInterface|UpdateInterface
     */
    public function rightJoin(RepositoryInterface $table, $column, ?OperatorInterface $operator = null): JoinInterface;

    /**
     * @param RepositoryInterface $table
     * @param $column
     * @param OperatorInterface|null $operator
     * @return JoinInterface|SelectInterface|UpdateInterface
     */
    public function innerJoin(RepositoryInterface $table, $column, ?OperatorInterface $operator = null): JoinInterface;

    /**
     * @param RepositoryInterface $table
     * @param $column
     * @param OperatorInterface|null $operator
     * @return JoinInterface|SelectInterface|UpdateInterface
     */
    public function fullOuterJoin(RepositoryInterface $table, $column, ?OperatorInterface $operator = null): JoinInterface;
}
