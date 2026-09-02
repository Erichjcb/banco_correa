<?php

class UsuarioModel {

    private $db;

    public function __construct() {
        $this->db = Database::conectar();
    }


    public function verificarLogin($usuario, $password) {

        $sql = "SELECT * FROM usuarios 
                WHERE usuario = '$usuario' 
                AND password = '$password'";

        $resultado = $this->db->query($sql);

        if ($resultado && $resultado->num_rows > 0) {
            return $resultado->fetch_assoc();
        }

        return false;
    }

    
    public function obtenerUsuario($id) {

        $sql = "SELECT * FROM usuarios WHERE id = $id";

        $resultado = $this->db->query($sql);

        if ($resultado && $resultado->num_rows > 0) {
            return $resultado->fetch_assoc();
        }

        return false;
    }


    public function actualizarSaldo($id, $nuevoSaldo) {

        $sql = "UPDATE usuarios 
                SET SALDO = $nuevoSaldo 
                WHERE id = $id";

        return $this->db->query($sql);
    }
}
?>
