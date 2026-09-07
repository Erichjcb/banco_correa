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
        // para conectarse con la base de datos :SillyDev:
        require_once '../config/database.php'; // "importamos" el database.php
        $db = Database::conectar(); //conectamos
        $sql = "
        SELECT *
        FROM usuarios;
        "; #Consulta para el sql :SillyDev:
        $usuarios = $db->query($sql); //hacer la consulta a nuestra base de datos con el $sql
        //$usuarios = $resultado->fetch_assoc()['COUNT(usuario)'];
        foreach ($usuarios as $usuario): ?>
        <tr>
            <td><?= $usuario['id'] ?></td>
            <td><?= htmlspecialchars($usuario['usuario']) ?></td>
            <td>$<?= number_format($usuario['saldo'], 2) ?></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>
</div>

<?php include 'partials/footer.php'; ?>