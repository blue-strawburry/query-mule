<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Common\Sql;


use Redstraw\Hooch\Query\Exception\SqlException;
use Redstraw\Hooch\Query\Sql\Statement\FilterInterface;

/**
 * Trait HasOrWhereNotBetween
 * @package Redstraw\Hooch\Query\Common\Sql
 */
trait HasOrWhereNotBetween
{
    /**
     * @param string|null $column
     * @param $from
     * @param $to
     * @return FilterInterface
     * @throws SqlException
     */
    public function orWhereNotBetween(?string $column, $from, $to): FilterInterface
    {
        if($this instanceof FilterInterface) {
            $this->orWhereNot(
                $column,
                $this->operator()
                    ->logical()
                    ->param()
                    ->omitTrailingSpace()
                    ->between($from, $to)
            );

            return $this;
        }else {
            throw new SqlException(sprintf("Must invoke FilterInterface in: %s.", get_class($this)));
        }
    }
}
