<?php

$dsn = "pgsql:host=db;dbname=portafolio_db";
$user = "usuario_p1"; $pass = "password123";

try { 
    $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]); 
} catch (PDOException $e) { 
    die("ERROR DE CONEXIÓN: " . $e->getMessage()); 
}

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

// 3. CONSULTA DINÁMICA DE ALUMNOS (NUEVO)
$stmt_alumnos = $pdo->query("SELECT * FROM alumnos ORDER BY id ASC");
$alumnos = $stmt_alumnos->fetchAll(PDO::FETCH_ASSOC);

// 4. LECTURA DE REPORTE PYTHON
$archivo_reporte = 'reports/reporte.txt';
$contenido_reporte = file_exists($archivo_reporte) ? file_get_contents($archivo_reporte) : "Calculando reporte...";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Buzón de sugerencias</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .navbar { background-color: #1a237e !important; }
        #radio-portafolio, #radio-buzon { display: none; }
        #seccion-portafolio, #seccion-buzon { display: none; }
        #radio-buzon:checked ~ .container #seccion-buzon { display: block; }
        #radio-portafolio:checked ~ .container #seccion-portafolio { display: block; }
        .nav-toggle {
            cursor: pointer; padding: 8px 15px; border-radius: 5px; color: white;
            border: 1px solid rgba(255,255,255,0.5); transition: 0.3s; display: inline-block; margin-left: 10px;
        }
        #radio-buzon:checked ~ .navbar .btn-buzon,
        #radio-portafolio:checked ~ .navbar .btn-portafolio {
            background-color: white; color: #1a237e; font-weight: bold;
        }
        .card-custom { background: white; border-radius: 12px; border: none; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .profile-img { width: 100px; height: 100px; object-fit: cover; border-radius: 50%; border: 3px solid #3f51b5; }
        .status-badge { font-size: 0.8rem; padding: 5px 10px; border-radius: 20px; }
        .reporte-box { background-color: #e8eaf6; border-left: 5px solid #1a237e; padding: 15px; border-radius: 8px; }
    </style>
</head>
<body>

    <input type="radio" name="vista" id="radio-buzon" checked>
    <input type="radio" name="vista" id="radio-portafolio">

    <nav class="navbar navbar-expand-lg navbar-dark shadow mb-4">
        <div class="container">
            <span class="navbar-brand"></span>
            <div>
                <label for="radio-buzon" class="nav-toggle btn-buzon">Buzón</label>
                <label for="radio-portafolio" class="nav-toggle btn-portafolio">Portafolio</label>
            </div>
        </div>
    </nav>

    <div class="container">
        <div id="seccion-buzon">
            <h2 class="text-center mb-4" style="color: #1a237e;">Buzón de sugerencias</h2>
            
            <div class="reporte-box mb-4 shadow-sm">
                <h6 class="text-uppercase fw-bold text-muted small mb-1">Sugerencias grabadas</h6>
                <p class="mb-0 fw-bold"><?php echo $contenido_reporte; ?></p>
            </div>

            <div class="card card-custom p-4 mb-4">
                <form method="POST">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label font-weight-bold">Tema</label>
                            <input type="text" name="tema" class="form-control" placeholder="Escriba aquí su tema..." required>
                        </div>
                        <div class="col-md-8 mb-3">
                            <label class="form-label font-weight-bold">Sugerencia</label>
                            <textarea name="detalle" class="form-control" placeholder="Desarrolle su sugerencia..." required></textarea>
                        </div>
                    </div>
                    <button type="submit" name="agregar" class="btn btn-primary w-100 fw-bold">Enviar sugerencia</button>
                </form>
            </div>

            <div class="table-responsive card card-custom p-3">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr><th>TEMA</th><th>DESCRIPCION</th><th>ESTADO</th><th>ACCIONES</th></tr>
                    </thead>
                    <tbody>
                        <?php 
                        $stmt = $pdo->query("SELECT * FROM sugerencias ORDER BY id DESC");
                        while ($row = $stmt->fetch()): 
                        ?>
                        <tr>
                            <td><span class="badge bg-secondary"><?php echo $row['tema']; ?></span></td>
                            <td><?php echo $row['detalle_sugerencia']; ?></td>
                            <td>
                                <?php if($row['revisada']): ?>
                                    <span class="status-badge bg-success text-white">Leído</span>
                                <?php else: ?>
                                    <span class="status-badge bg-warning text-dark">No leído</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="index.php?toggle=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-info">Cambiar</a>
                                <a href="index.php?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Eliminar?')">Eliminar</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div id="seccion-portafolio">
            <h2 class="text-center mb-5" style="color: #1a237e;">Sobre nosotros</h2>
            <div class="row g-4 text-center">
                
                <?php foreach ($alumnos as $alumno): ?>
                <div class="col-md-6">
                    <div class="card card-custom p-4 h-100">
                        <img src="<?php echo $alumno['foto']; ?>" class="profile-img mx-auto mb-3" alt="Foto">
                        <h3><?php echo $alumno['nombre']; ?></h3>
                        <p class="text-muted fw-bold"><?php echo $alumno['rol']; ?></p>
                        <p class="px-3"><?php echo $alumno['bio']; ?></p>
                        <hr>
                        <p class="small text-primary"><strong>Habilidades:</strong> <?php echo $alumno['habilidades']; ?></p>
                    </div>
                </div>
                <?php endforeach; ?>

            </div>
        </div>
    </div>
</body>
</html>