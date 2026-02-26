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

