<?php

class Database {

    public static function conectar() {

        $conexion = new mysqli(
            "localhost",
            "root",
            "",
            "banco_correa",
            3307
        );

        if ($conexion->connect_error) {
            die("Error de conexión: " . $conexion->connect_error);
        }

        $conexion->set_charset("utf8");

        return $conexion;
    }
}