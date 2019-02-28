<?php

require_once '../tools/db/PgSqlConnection.php';


class hora
{
    private $time;

    function __construct()
    {
        $this->time = date('Y-m-d h:i:s');
    }

    function getTime()
    {
        $sql = "INSERT INTO test (create_date)
                VALUES(?)";

        $query = PgSqlConnection::connection()->prepare($sql);
        $query->execute([
            $this->time
        ]);

        print_r($query->errorInfo());

    }

}

$hora = new hora;

$hora->getTime();