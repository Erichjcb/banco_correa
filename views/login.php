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
//iniciamos el almacenamiento de sesion solo si no ha sido activado previamente
if(session_status() === PHP_SESSION_NONE){
    session_start();
}
//importamos la clase de conexion a la base de datos de forma segura
require_once '../config/conexion.php';
//inicializamos variables de control y resultado de autenticacion
$mensaje = false;
$usuarioLogueado = null;

//evaluamos si la peticion entrante fue enviada mediante el metodo POST
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    //limpiamos y asignamos las credenciales enviadas desde el formulario
    $usuario = trim($_POST['UsuarioNombre'] ?? '');
    $contra = trim($_POST['UsuarioContra'] ?? '');

    //validamos que los campos obligatorios no esten vacios
    if(!empty($usuario) && !empty($contra)){
        //obtenemos el enlace activo con la base de datos MySQL
        $db = Database::conectar();
        //preparamos la sentencia SQL parametrizada para prevenir inyecciones SQL
        $stmt = $db->prepare("SELECT id, usuario, password, saldo FROM usuarios WHERE usuario = ? LIMIT 1");
        //asociamos el parametro de texto ingresado por el usuario
        $stmt->bind_param("s", $usuario);
        //ejecutamos la sentencia preparada en el motor de base de datos
        $stmt->execute();
        //obtenemos el conjunto de resultados retornado por la consulta
        $resultado = $stmt->get_result();
        //convertimos el primer registro encontrado en un arreglo asociativo
        $fila = $resultado->fetch_assoc();

        //verificamos si el usuario existe y si su clave coincide (soporta password_hash o texto plano)
        if($fila && (password_verify($contra, $fila['password']) || $contra === $fila['password'])){
            //guardamos los datos esenciales del usuario en la sesion actual
            $_SESSION['id_usuario'] = $fila['id'];
            $_SESSION['UsuarioNombre'] = $fila['usuario'];
            //asignamos los datos obtenidos a la variable de vista
            $usuarioLogueado = $fila;
        }else{
            //marcamos la bandera de error si las credenciales fallan
            $mensaje = true;
        }
        //cerramos la consulta preparada para liberar recursos del servidor
        $stmt->close();
    }else{
        //si envio campos vacios se activa la alerta de error
        $mensaje = true;
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