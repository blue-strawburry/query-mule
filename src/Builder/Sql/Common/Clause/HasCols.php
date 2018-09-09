<?php

namespace QueryMule\Builder\Sql\Common\Clause;


use QueryMule\Builder\Sql\Common\Common;
use QueryMule\Builder\Sql\Mysql\Filter;
use QueryMule\Query\Sql\Sql;

/**
 * Trait HasCols
 * @package QueryMule\Builder\Sql\Common\Clause
 */
trait HasCols
{
    use Common;

    /**
     * @param array $cols
     * @param null $alias
     * @return $this
     */
    public function cols($cols = [Sql::SQL_STAR], $alias = null)
    {
        $i = 0;
        foreach ($cols as $key => &$col) {
            if ((int)$key !== $i) {
                $i++; //Increment only when using int positions
            }

            $sql = $this->columnClause(
                ($col !== Sql::SQL_STAR) ? $this->accent()->append($col) : $col,
                !empty($alias) ? $this->accent()->append($alias) : $alias,
                ($key !== $i) ? $key : null,
                !empty($this->query()->get(Sql::COLS))
            );

            $this->query()->add(Sql::COLS, $sql);
        }

        return $this;
    }

    /**
     * @param string $column
     * @param bool $alias
     * @param bool $as
     * @param bool $comma
     * @return Sql
     */
    private function columnClause($column, $alias = false, $as = false, $comma = false)
    {
        $sql = null;
        $sql .= !empty($comma) ? ',' : '';
        $sql .= !empty($alias) ? $alias.'.' : '';
        $sql .= $column;
        $sql .= !empty($as) ? Sql::SQL_SPACE.Sql::AS.Sql::SQL_SPACE.$as : '';

        return new Sql($sql);
    }
}
