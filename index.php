<?php

require_once 'config/conexion.php';
require_once 'models/UsuarioModel.php';
require_once 'controllers/BancoController.php';


$accion = isset($_GET['accion'])
    ? $_GET['accion']
    : 'inicio';


$controlador = new BancoController();


switch ($accion) {

    case 'login':

        $controlador->login();

        break;


    case 'retiro':

        $controlador->retiro();

        break;


    default:

        echo "Bienvenido al Sistema Bancario.<br><br>";

        echo "Pruebas disponibles:<br>";

        echo "Login:<br>";
        echo "?accion=login&u=admin&p=1234<br><br>";

        echo "Retiro:<br>";
        echo "?accion=retiro&monto=200<br>";

        break;
}

?>
