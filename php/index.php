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
