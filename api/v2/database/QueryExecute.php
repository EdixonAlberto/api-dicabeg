<?php

namespace V2\Database;

class QueryExecute
{
    private $select;

    public function __construct($sql)
    {
        $this->select = $sql;
    }

    public function where($column, $value)
    {
        $sql = $this->select . " WHERE {$column} = ?";
        $where = new Get($sql, $value);
        return $where;

        // $query = self::connection()->prepare($sql);
        // $query->execute([
        //     $value
        // ]);
        // return $query->fetch(\PDO::FETCH_OBJ);

/* TODO
        $rows = $query->rowCount();
        if ($rows == false) return false;

        for ($i = 0; $i < $rows; $i++) {
            $objIndexedByColumns = $query->fetch(\PDO::FETCH_OBJ);
            $arrayResponse[] = $objIndexedByColumns;
        }
        return $arrayResponse;
         */
    }
}
