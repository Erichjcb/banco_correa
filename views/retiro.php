<?php include('partials/header.php'); ?>
<?php include('partials/nav.php'); ?>

<div class = 'container mt-4'>
    <h2>Retiro</h2>
    <form action="" method="POST"> <!--Esto es para el retiro del usuario, obetniendo Usuario y Contraseña-->
        <span for="UsuarioMonto">Monto a sacar: </span> <br>
        <input class = 'retiro_f' type="number" id="UsuarioMonto" required 
        placeholder = "S/ 00.00" name="UsuarioMonto"><br><br>
        <span for="UsuarioContra">Contraseña: </span> <br>
        <input class = 'retiro_f' type="password" id="UsuarioContra" required 
        placeholder = "contraseña" name="UsuarioContra"><br><br>
        <button type="submit" class = "retiro_f">
            RETIRAR
        </button>
    </form>
    <?php 
    session_start(); //Para poder usar datos de login.php LOL | mantener sesion iniciada...
    require_once '../config/conexion.php';
    require_once '../models/UsuarioModel.php';
    require_once '../controllers/BancoController.php';
        $usuario = isset($_SESSION['UsuarioNombre']) ? $_SESSION['UsuarioNombre'] : '';
        $contraUsuario = isset($_SESSION['UsuarioContra']) ? $_SESSION['UsuarioContra'] : '';
        $mensaje = false;
        if(isset($_POST['UsuarioMonto']) == '' || isset($_POST['UsuarioMonto']) == null){ return; }
            $db = Database::conectar(); //Para consultar en la base de datos de nuevo XD
            $sql = "SELECT * FROM usuarios WHERE usuario = '$usuario' AND password = '$contraUsuario'"; 
            #Consulta para el sql :SillyDev:
            $SoloUsuario = $db->query($sql)->fetch_assoc();
            $controlador = new BancoController();
            $nuevoSlado = $controlador->retiro($_POST['UsuarioMonto'], $SoloUsuario['id']);
        
        if ($mensaje): ?>
        <div class = 'alert <?= strpos($mensaje, 'ERROR') !== false ? 'alert-danger' : 'alert-success' ?>'>
            <?= $mensaje ?>
        </div>
        <?php endif; ?>
    <p>Saldo actual: $<?= number_format($nuevoSlado, 2) ?></p>
    <p>Nuevo saldo: $<?= number_format($_POST['UsuarioMonto'], 2) ?></p>
</div>

<?php include 'partials/footer.php'; ?>
</div>