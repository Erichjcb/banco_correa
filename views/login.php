<?php include 'partials/header.php'; ?>
<?php include 'partials/nav.php'; ?>

<div class="container mt-4">
    <h2>Login</h2>
    <!--action sera "retiro.php" para enviar solo el nombre de usuario a la pagina-->
    <form action="" method="POST"> 
        <!--Esto es para el inicio de sesion, obteniendo Usuario y Contraseña-->
        <span for="UsuarioNombre">Usuario: </span> <br>
        <input class = 'i_s' type="text" id="UsuarioNombre" required 
        placeholder = "Usuario" name="UsuarioNombre"><br><br> 
    
        <span for="UsuarioContra">Contraseña: </span> <br>
        <input class = 'i_s' type="password" id="UsuarioContra" required 
        placeholder = "contraseña" name="UsuarioContra"><br><br>

        <button type="submit" class = "i_s">
            Iniciar Sesion
        </button>
    </form>
    <?php 
        session_start(); //Inicia la Sesion LOL
        $contador_intentos = 0; //contador de intentos de inisio de seccion :SillyDev:
        $usuario = isset($_POST['UsuarioNombre']) ? $_POST['UsuarioNombre'] : ''; //para identificar por el nombre del input
        $contra = isset($_POST['UsuarioContra']) ? $_POST['UsuarioContra'] : '';
        $mensaje = false;
        $usuarioLogueado = null;
        if(($usuario == '' || $contra == '') && $contador_intentos < 1) {
            
        }
        else{
            require_once '../config/conexion.php'; // "importamos" el database.php
            $db = Database::conectar(); //conectamos
            $sql = "SELECT * FROM usuarios WHERE usuario = '$usuario' AND password = '$contra'"; 
            #Consulta para el sql :SillyDev:
            $usuarioLogueado = $db->query($sql)->fetch_assoc(); //Convierte en "lista" el resultado de la consulta
            if(isset($usuarioLogueado['usuario']) == '' && isset($usuarioLogueado['password']) == ''){
                $mensaje = true; //si nombre de usuario y contraseña no existen dentro de db, entonces son credenciales incorrectas :SillyDev:
            }
            else{
                $_SESSION['UsuarioNombre'] = $usuarioLogueado['usuario'];
                $_SESSION['UsuarioContra'] = $usuarioLogueado['password'];
            }
        }
        if ($mensaje): ?>
        <div class="alert alert-info"><?= "Ingrese correctamente sus credenciales" ?></div>
        <?php endif; ?>

        <?php 
        if ($usuarioLogueado && !$mensaje): ?>
        <div class="card">
            <div class="card-body">
            <h5 class="card-title">Bienvenido, <?= htmlspecialchars($usuarioLogueado['usuario']) ?></h5>
            <p class="card-text">Saldo actual: $<?= number_format($usuarioLogueado['saldo'], 2) ?></p>
            </div>
        </div>
        <?php 
        ?>
        <?php endif; ?>
</div>

<?php include 'partials/footer.php'; ?>