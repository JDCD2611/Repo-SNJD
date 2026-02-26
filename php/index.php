<?php
$dsn = "pgsql:host=db;dbname=portafolio_db";
$user = "usuario_p1";
$pass = "password123";

try {
    $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
} catch (PDOException $e) { 
    die("ERROR: " . $e->getMessage()); 
}

if (isset($_POST['agregar'])) {
    $stmt = $pdo->prepare("INSERT INTO tickets (usuario, descripcion_falla) VALUES (?, ?)");
    $stmt->execute([$_POST['usuario'], $_POST['falla']]);
    header("Location: index.php");
}

if (isset($_GET['toggle'])) {
    $stmt = $pdo->prepare("UPDATE tickets SET resuelto = NOT resuelto WHERE id = ?");
    $stmt->execute([$_GET['toggle']]);
    header("Location: index.php");
}

if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM tickets WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    header("Location: index.php");
}

$reporte = file_exists('reports/reporte.txt') ? file_get_contents('reports/reporte.txt') : "NO HAY DATOS";
?>

<html>
<head>
    <title>Soporte tecnico</title>
</head>
<body>

    <center>
        <h1>Sistema de tickets</h1>
        <p>Alumno: Jorge Chirinos</p>
    </center>

    <hr>

    <h3>Reporte:</h3>
    <table border="1">
        <tr>
            <td><?php echo $reporte; ?></td>
        </tr>
    </table>

    <hr>

    <h3>Crear nuevo reporte</h3>
    <form method="POST">
        Nomnbre de usuario: <br>
        <input type="text" name="usuario" required> <br>
        Descripcion de la falla: <br>
        <textarea name="falla" required></textarea> <br>
        <input type="submit" name="agregar" value="ENVIAR DATOS">
    </form>

    <hr>

    <h3>Tickets creados</h3>
    <table border="1" width="100%">
        <tr>
            <th>ID</th>
            <th>Usuario</th>
            <th>Falla</th>
            <th>Estado</th>
            <th>selecciones</th>
        </tr>
        <?php
        $stmt = $pdo->query("SELECT * FROM tickets ORDER BY id ASC");
        while ($row = $stmt->fetch()): ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['usuario']; ?></td>
            <td><?php echo $row['descripcion_falla']; ?></td>
            <td><?php echo $row['resuelto'] ? 'Revisado' : 'En espera'; ?></td>
            <td>
                <a href="index.php?toggle=<?php echo $row['id']; ?>">Cambiar</a> |
                <a href="index.php?delete=<?php echo $row['id']; ?>">Eliminar</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

</body>
</html>