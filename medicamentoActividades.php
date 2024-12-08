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

    $id_profesional = $_SESSION['id_profesional'] ?? null;

    // Consulta para obtener los pacientes con citas reservadas para el profesional
    $query = "SELECT usuarios.primer_apellido AS paciente, citas.id_citas 
              FROM usuarios
              INNER JOIN citas
              ON usuarios.id_usuarios = citas.id_usuarios
              WHERE citas.id_profesionales = ? AND citas.estado = 'reservada'";
    $citas = [];
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param('i', $id_profesional);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $citas = $result->fetch_all(MYSQLI_ASSOC);
        } else {
            $_SESSION['mensaje'] = "No tienes citas reservadas con pacientes.";
            $_SESSION['tipo'] = "alert-warning";
        }
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
        <title>Clínica VitalMente | Agregar Medicamento y Actividad</title>
        <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
        <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
    </head>
    <body>
        <!-- Mostrar mensaje de alerta -->
        <?php if (isset($_SESSION['mensaje'])): ?>
            <div class="alert <?= $_SESSION['tipo']; ?> text-center" role="alert">
                <?= $_SESSION['mensaje']; ?>
            </div>
            <?php unset($_SESSION['mensaje'], $_SESSION['tipo']); ?>
        <?php endif; ?>

        <div class="container my-5">
            <h1 class="text-center mb-4">Agregar Medicamento y Actividad</h1>
            <form action="insertarMedAct.php" method="post">
                <div class="row">
                    <!-- Select para elegir al paciente -->
                    <div class="col-md-6 mb-3">
                        <label for="paciente" class="form-label">Seleccionar Paciente</label>
                        <select name="id_citas" id="paciente" class="form-select">
                            <option value="" disabled selected>Elige un paciente</option>
                            <?php foreach ($citas as $cita): ?>
                                <option value="<?= htmlspecialchars($cita['id_citas']); ?>">
                                    <?= htmlspecialchars($cita['paciente']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Nombre del medicamento -->
                    <div class="col-md-6 mb-3">
                        <label for="medicamento" class="form-label">Nombre del Medicamento</label>
                        <input type="text" name="nombre_medicamento" id="medicamento" class="form-control" required>
                    </div>
                </div>

                <div class="row">
                    <!-- Dosis -->
                    <div class="col-md-6 mb-3">
                        <label for="dosis" class="form-label">Dosis</label>
                        <input type="text" name="dosis" id="dosis" class="form-control" required>
                    </div>

                    <!-- Instrucciones -->
                    <div class="col-md-6 mb-3">
                        <label for="instrucciones" class="form-label">Instrucciones</label>
                        <input type="text" name="instrucciones" id="instrucciones" class="form-control" required>
                    </div>
                </div>

                <div class="row">
                    <!-- Actividades -->
                    <div class="col-12 mb-3">
                        <label for="actividad" class="form-label">Actividad</label>
                        <textarea name="actividad" id="actividad" class="form-control" rows="4" required></textarea>
                    </div>
                </div>

                <!-- Botón para agregar -->
                <div class="text-center">
                    <button type="submit" class="btn btn-success">Agregar Medicamento y Actividad</button>
                </div>
            </form>
            <a href="profesionales.php" class="btn btn-secondary mt-3">Regresar</a>
        </div>
    </body>
</html>