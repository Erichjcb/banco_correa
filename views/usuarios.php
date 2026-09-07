<?php include 'partials/header.php'; ?>
<?php include 'partials/nav.php'; ?>

<div class="container mt-4">
    <h2>Listado de Usuarios</h2>
    <div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Usuario</th>
            <th>Saldo</th>
        </tr>
        </thead>
        <tbody>
        <?php 
//importamos el archivo de base de datos usando una ruta confiable
require_once '../config/conexion.php';
//obtenemos la conexion activa con la base de datos
$db = Database::conectar();
//definimos la consulta seleccionando solo las columnas requeridas para optimizar la memoria
$sql = "SELECT id, usuario, saldo FROM usuarios ORDER BY id ASC";
//ejecutamos la consulta mediante el objeto mysqli
$resultado = $db->query($sql);
//recorremos cada registro de la tabla de usuarios fila por fila
if($resultado && $resultado->num_rows > 0):
    while($usuario = $resultado->fetch_assoc()): ?>
        <tr>
            <td><?= $usuario['id'] ?></td>
            <td><?= htmlspecialchars($usuario['usuario']) ?></td>
            <td>$<?= number_format($usuario['saldo'], 2) ?></td>
        </tr>
        <?php 
    endwhile;
    //liberamos el espacio ocupado por el conjunto de resultados
    $resultado->free();
endif;
        ?>
        </tbody>
    </table>
    </div>
</div>

<?php include 'partials/footer.php'; ?>