<?php
    // Verifica si hay una sesión activa
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Configuración de conexión con la base de datos
    include_once("config.php");

    // Verifica que el usuario esté autenticado como profesional
    if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] !== 'profesional') {
        header("Location: acceder.php");
        exit();
    }

    $id_usuario = $_SESSION['id_usuarios']; 

    $query_profesional = "SELECT id_profesionales FROM profesionales 
                          WHERE id_usuario = ?";
    if ($stmt = $conn->prepare($query_profesional)) {
        $stmt->bind_param('i', $id_usuario);
        $stmt->execute();
        $stmt->bind_result($id_profesionales);
        if ($stmt->fetch()) {
            $id_profesional = $id_profesionales;
        } else {
            die("Error: No se encontró un ID de profesional para este usuario.");
        }
        $stmt->close();
    } else {
        die("Error en la consulta: " . $conn->error);
    }

    // Consulta para obtener las citas del profesional
    $query = "SELECT 
                citas.id_citas,
                usuarios.nombre AS paciente,
                servicios.nombre_servicio AS servicio,
                citas.fecha,
                citas.estado
            FROM citas
            INNER JOIN usuarios ON citas.id_usuarios = usuarios.id_usuarios
            INNER JOIN servicios ON citas.id_servicios = servicios.id_servicios
            WHERE citas.id_profesionales = ? AND citas.estado = 'reservada'
            ORDER BY citas.fecha ASC";

    $citas = [];
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param('i', $id_profesional);
        $stmt->execute();
        $result = $stmt->get_result();
        $citas = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    } else {
        echo "Error en la consulta: " . $conn->error;
    }
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Clínica VitalMente | Editar Citas</title>
        <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
        <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
    </head>
    <body>
        <!-- Mostrar mensaje -->
        <?php if(isset($_SESSION['mensaje'])): ?>
            <div class="alert <?= $_SESSION['tipo']; ?> text-center" role="alert">
                <?= $_SESSION['mensaje']; ?>
            </div>
            <?php
                unset($_SESSION['mensaje'], $_SESSION['tipo']);
            ?>
        <?php endif; ?>

        <div class="container my-5">
            <h1 class="text-center mb-4">Citas Programadas</h1>

            <!-- Mostrar citas -->
            <?php if (!empty($citas)): ?>
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                    <?php foreach ($citas as $cita): ?>
                        <div class="col">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title">Paciente: <?= htmlspecialchars($cita['paciente']); ?></h5>
                                    <p class="card-text">
                                        <strong>Servicio:</strong> <?= htmlspecialchars($cita['servicio']); ?><br>
                                        <strong>Fecha:</strong> <?= htmlspecialchars($cita['fecha']); ?><br>
                                        <strong>Estado:</strong> <?= htmlspecialchars($cita['estado']); ?>
                                    </p>
                                </div>
                                <div class="card-footer text-center">
                                    <!-- Botón  -->
                                    <form action="eliminar_citaprof.php" method="post">
                                        <input type="hidden" name="id_citas" value="<?= htmlspecialchars($cita['id_citas']); ?>">
                                        <button type="submit" class="btn btn-danger w-100">Cancelar Cita</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-center">No tienes citas programadas actualmente.</p>
            <?php endif; ?>
            <a href="profesionales.php" class="btn btn-secondary mt-5">Regresar</a>
        </div>
    </body>
</html>