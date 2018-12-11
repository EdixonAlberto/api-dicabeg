<?php
    include_once 'dataBase.php';

    class userQuerys {
        private $data;

        public function __construct() {
            $this->data = new dataBase('mysql');
        }

        function get() {
            $list["users"] = array();

            $sql = "SELECT * FROM users";
            $query = $this->data->connect()->query($sql);

            foreach ($query as $row) {
                $item = array(
                    'name'      => $row['name'],
                    'age'       => $row['age'],
                    'user'      => $row['user'],
                    'password'  => $row['password'],
                    'NIR'       => $row['NIR'],
                    'phone'     => $row['phone']
                );
                array_push($list["users"], $item);
            }
            return json_encode($list);
        }

        // function add($table, $nombre, $edad, $usuario, $clave, $NIR, $telefono) {
        //     $sql = "INSERT INTO $table(id, name, age, user, clave, NIR, phone)
        //             VALUES('',$nombre, $edad, $usuario, $clave, $NIR, $telefono)";
        //     echo $sql;
        //     $consulta = $datos->conectar()->query($sql);
        // }

        function update($table) {

        }

        function remove($table) {

        }
    }
?>