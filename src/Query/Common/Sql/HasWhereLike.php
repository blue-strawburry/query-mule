<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Common\Sql;


use Redstraw\Hooch\Query\Exception\SqlException;
use Redstraw\Hooch\Query\Sql\Statement\FilterInterface;

/**
 * Trait HasWhereLike
 * @package Redstraw\Hooch\Query\Common\Sql
 */
trait HasWhereLike
{
    /**
     * @param string|null $column
     * @param $value
     * @return FilterInterface
     * @throws SqlException
     */
    public function whereLike(?string $column, $value): FilterInterface
    {
        if($this instanceof FilterInterface) {
            $this->where(
                $column,
                $this->operator()
                    ->logical()
                    ->param()
                    ->omitTrailingSpace()
                    ->like($value)
            );

            return $this;
        }else {
            throw new SqlException(sprintf("Must invoke FilterInterface in: %s.", get_class($this)));
        }
    }
}
