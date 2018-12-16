<?php

declare(strict_types=1);

namespace QueryMule\Builder\Sql\Common\Clause;


use QueryMule\Builder\Sql\Common\Common;
use QueryMule\Query\Sql\Statement\FilterInterface;

/**
 * Trait HasWhereLike
 * @package QueryMule\Builder\Sql\Common\Clause
 */
trait HasWhereLike
{
    use Common;

    /**
     * @param $column
     * @param $value
     * @return $this
     */
    public function whereLike($column, $value)
    {
        if($this instanceof FilterInterface) {
            $this->where($column, $this->logical()->omitTrailingSpace()->like($value));
        }

        return $this;
    }
}
