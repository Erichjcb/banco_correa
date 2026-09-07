<?php

require_once 'config/conexion.php';
require_once 'models/UsuarioModel.php';
require_once 'controllers/BancoController.php';


$accion = isset($_GET['accion'])
    ? $_GET['accion']
    : 'inicio';


$controlador = new BancoController();
?>
<?php include 'views/partials/header.php'; ?>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
    <a class="navbar-brand" href="index.php">Banco Correa</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="views/inicio.php">Inicio</a></li>
        <li class="nav-item"><a class="nav-link" href="views/login.php">Login</a></li>
        <li class="nav-item"><a class="nav-link" href="views/retiro.php">Retiro</a></li>
        <li class="nav-item"><a class="nav-link" href="views/usuarios.php">Usuarios</a></li>
        </ul>
    </div>
    </div>
</nav>

<div class="container mt-4">
    <div class="p-5 bg-light rounded-3">
    <h1 class="display-4">Bienvenido al Sistema Bancario "BANCO CORREA"</h1>
    <p class="lead">Accede a las funciones desde el menú o mediante la URL.</p>
    <hr class="my-4">
    <p>Prueba: <code>Navegando en la barra superior.</code></p>
    </div>
</div>

<?php 
include 'views/partials/footer.php';

switch ($accion) {

    case 'login':

        $controlador->login();

        break;


    case 'retiro':

        $controlador->retiro();

        break;


    default:

        echo "Bienvenido al Sistema Bancario.<br><br>";

        //echo "Pruebas disponibles:<br>";

        //echo "Login:<br>";
        //echo "?accion=login&u=admin&p=1234<br><br>";

        //echo "Retiro:<br>";
        //echo "?accion=retiro&monto=200<br>";

        break;
}

?>
