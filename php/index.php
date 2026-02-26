<?php
$dsn = "pgsql:host=db;dbname=portafolio_db";
$user = "usuario_p1"; $pass = "password123";
try { $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]); } 
catch (PDOException $e) { die("ERROR: " . $e->getMessage()); }

if (isset($_POST['agregar'])) {
    $stmt = $pdo->prepare("INSERT INTO sugerencias (tema, detalle_sugerencia) VALUES (?, ?)");
    $stmt->execute([$_POST['tema'], $_POST['detalle']]);
    header("Location: index.php");
}
if (isset($_GET['toggle'])) {
    $stmt = $pdo->prepare("UPDATE sugerencias SET revisada = NOT revisada WHERE id = ?");
    $stmt->execute([$_GET['toggle']]);
    header("Location: index.php");
}
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM sugerencias WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    header("Location: index.php");
}

$reporte = file_exists('reports/reporte.txt') ? file_get_contents('reports/reporte.txt') : "SIN REPORTE";
?>

<html>
<body>
    <center><h1>Buzon de sugerencias</h1></center>
    <hr>
    <h3>Sistema de reporte):</h3>
    <table border="1"><tr><td><?php echo $reporte; ?></td></tr></table>
    <hr>

    <h3>Enviar sugerencia</h3>
    <form method="POST">
        tema: <input type="text" name="tema" required><br>
        detalle: <textarea name="detalle" required></textarea><br>
        <input type="submit" name="agregar" value="ENVIAR SUGERENCIA">
    </form>
    <hr>

    <h3>Sugerencias recibidas</h3>
    <table border="1" width="100%">
        <tr>
            <th>ID</th><th>Tema</th><th>Detalles</th><th>Estado</th><th>Acciones</th>
        </tr>
        <?php $stmt = $pdo->query("SELECT * FROM sugerencias ORDER BY id ASC");
        while ($row = $stmt->fetch()): ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['tema']; ?></td>
            <td><?php echo $row['detalle_sugerencia']; ?></td>
            <td><?php echo $row['revisada'] ? 'Revisado' : 'En espera'; ?></td>
            <td>
                <a href="index.php?toggle=<?php echo $row['id']; ?>">CAMBIAR</a> | 
                <a href="index.php?delete=<?php echo $row['id']; ?>">BORRAR</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>