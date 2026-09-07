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
//iniciamos sesion para verificar si el cliente ya esta identificado
if(session_status() === PHP_SESSION_NONE){
    session_start();
}
//requerimos los archivos de conexion a la base de datos
require_once '../config/conexion.php';

//declaramos las variables base para controlar la vista del retiro
$mensaje = false;
$saldoActual = 0.00;
$montoRetirado = 0.00;
$nombreUsuario = $_SESSION['UsuarioNombre'] ?? '';

//verificamos si existe una sesion activa de usuario para proceder
if(!empty($nombreUsuario)){
    //conectamos con la base de datos
    $db = Database::conectar();

    //obtenemos los datos actuales y actualizados del usuario autenticado
    $stmtUser = $db->prepare("SELECT id, usuario, password, saldo FROM usuarios WHERE usuario = ? LIMIT 1");
    //vinculamos el nombre de usuario de la sesion
    $stmtUser->bind_param("s", $nombreUsuario);
    //ejecutamos la lectura del saldo actual
    $stmtUser->execute();
    //extraemos los datos en formato asociativo
    $datosUsuario = $stmtUser->get_result()->fetch_assoc();
    //cerramos la declaracion
    $stmtUser->close();

    //si se encuentra el usuario, cargamos su saldo en pantalla
    if($datosUsuario){
        $saldoActual = (float)$datosUsuario['saldo'];
    }

    //procesamos el formulario de retiro cuando se envian datos por POST
    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['UsuarioMonto'], $_POST['UsuarioContra'])){
        //convertimos el monto a numero flotante
        $montoRetiro = (float)$_POST['UsuarioMonto'];
        //obtenemos la contrasena introducida para confirmar la operacion
        $contraIngresada = trim($_POST['UsuarioContra']);

        //validamos que el monto sea un valor numerico positivo
        if($montoRetiro <= 0){
            $mensaje = 'ERROR: Ingrese un monto valido mayor a cero.';
        //verificamos la clave ingresada con la almacenada en la base de datos
        }elseif(!$datosUsuario || (!password_verify($contraIngresada, $datosUsuario['password']) && $contraIngresada !== $datosUsuario['password'])){
            $mensaje = 'ERROR: Contraseña de autorización incorrecta.';
        //verificamos que el usuario cuente con saldo suficiente para el retiro
        }elseif($montoRetiro > $saldoActual){
            $mensaje = 'ERROR: Saldo insuficiente para realizar el retiro.';
        }else{
            //iniciamos una transaccion en la base de datos para asegurar consistencia
            $db->begin_transaction();
            try{
                //calculamos el saldo restante tras la operacion
                $nuevoCalculo = $saldoActual - $montoRetiro;
                //preparamos la actualizacion directa del saldo de la cuenta
                $stmtUpdate = $db->prepare("UPDATE usuarios SET saldo = ? WHERE id = ?");
                //asociamos el nuevo saldo y el identificador de usuario
                $stmtUpdate->bind_param("di", $nuevoCalculo, $datosUsuario['id']);
                //ejecutamos la modificacion
                $stmtUpdate->execute();
                //cerramos el statement de actualizacion
                $stmtUpdate->close();
                //confirmamos los cambios de la transaccion
                $db->commit();

                //actualizamos las variables que se mostraran en la interfaz
                $saldoActual = $nuevoCalculo;
                $montoRetirado = $montoRetiro;
                $mensaje = 'Retiro exitoso por S/ ' . number_format($montoRetiro, 2);
            }catch(Exception $e){
                //revertimos cualquier cambio pendiente en caso de error
                $db->rollback();
                $mensaje = 'ERROR: No se pudo completar la transaccion.';
            }
        }
    }
}else{
    //notificamos al usuario que debe autenticarse previamente
    $mensaje = 'ERROR: Debe iniciar sesion para poder realizar retiros.';
}
        
        if ($mensaje): ?>
        <div class = 'alert <?= strpos($mensaje, 'ERROR') !== false ? 'alert-danger' : 'alert-success' ?>'>
            <?= $mensaje ?>
        </div>
        <?php endif; ?>
    <p>Saldo actual: $<?= number_format($saldoActual, 2) ?></p>
    <p>Nuevo saldo: $<?= number_format($montoRetirado, 2) ?></p>
</div>

<?php include 'partials/footer.php'; ?>
</div>