<?php

    class connection{

        static public function connect(){

                $pdo = new PDO('mysql:host=localhost;dbname=pos_db;', 'root','');

                $pdo->exec('set names utf8');

                return $pdo;
        }
    }

?>