<?php 
// Verifica si hay una sesión activa.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Configuración de conexión con la base de datos.
include_once("config.php");

// Verifica si el usuario está autenticado.
if (!isset($_SESSION['tipo_usuario'])) {
    header("Location: acceder.php");
    exit();
}

$tipo_usuario = $_SESSION['tipo_usuario'];

// Incluye el header.
include_once("header/navegadorPrimario.php");
?> 
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Clínica VitalMente | Perfil</title>
        <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
        <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
        <link rel="stylesheet" href="css/estilosAdmin.css">
    </head>
    <body>
        <div class="container">
            <!-- Calendario para visualizar las citas de los profesionales. -->
            <h1 class="titulo">Calendario de profesionales</h1>
            <div id="calendario"></div>

            <!-- Citas agendadas hasta el momento. -->
            <h1 class="titulo">Citas Agendadas</h1>
            <?php
            $queryCitas = "SELECT 
                            usuarios.nombre AS usuario_nombre, 
                            citas.fecha, 
                            citas.hora, 
                            profesionales.nombre AS profesional_nombre, 
                            pagos.estado AS estado_pago, 
                            citas.id_citas 
                        FROM usuarios
                        INNER JOIN citas ON usuarios.id_usuarios = citas.id_usuarios
                        INNER JOIN pagos ON citas.id_citas = pagos.id_citas
                        INNER JOIN profesionales ON citas.id_profesionales = profesionales.id_profesionales
                        WHERE citas.estado = 'reservada'";

            $resultCitas = $conn->query($queryCitas);

            if ($resultCitas && $resultCitas->num_rows > 0) {
                echo '<div class="container">';
                echo '<table border="1">';
                echo "<tr>";
                echo "<th>PACIENTE</th>";
                echo "<th>PROFESIONAL</th>";
                echo "<th>FECHA</th>";
                echo "<th>HORA</th>";
                echo "<th>PAGO</th>";
                echo "<th>AJUSTES</th>";
                echo "</tr>";
                while ($cita = $resultCitas->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($cita['usuario_nombre']) . "</td>";
                    echo "<td>" . htmlspecialchars($cita['profesional_nombre']) . "</td>";
                    echo "<td>" . htmlspecialchars($cita['fecha']) . "</td>";
                    echo "<td>" . htmlspecialchars($cita['hora']) . "</td>";
                    echo "<td>" . htmlspecialchars($cita['estado_pago']) . "</td>";
                    echo '<td>
                            <form action="eliminar_citas.php" method="post">
                                <input type="hidden" name="id_citas" value="' . htmlspecialchars($cita['id_citas']) . '">
                                <button type="submit">Eliminar</button>
                            </form>
                        </td>';
                    echo "</tr>";
                }
                echo "</table>";
                echo "</div>";
            } else {
                echo '<h1 class="error">No existen citas agendadas</h1>';
            }
            $resultCitas->free();
            ?>

            <!-- Editar Servicios. -->
            <h1 class="titulo">Servicios</h1>
            <div class="card-deck">
                <?php
                $queryServicios = "SELECT * FROM servicios 
                                    WHERE estado != 'eliminado' 
                                    AND nombre_servicio IN ('Ansiedad','Terapia familiar','Adicciones')";
                $resultServicios = $conn->query($queryServicios);

                if ($resultServicios && $resultServicios->num_rows > 0) {
                    while ($servicio = $resultServicios->fetch_assoc()) {
                        ?>
                        <div class="card">
                            <h1 class="card-title"><?= htmlspecialchars($servicio['nombre_servicio']); ?></h1>
                            <img src="<?= htmlspecialchars($servicio['imagen']); ?>" class="card-img-top" alt="<?= htmlspecialchars($servicio['nombre_servicio']); ?>">
                            <div class="card-body">
                                <p class="card-text"><?= htmlspecialchars($servicio['descripcion']); ?></p>
                                <p class="card-text"><?= htmlspecialchars($servicio['asistencia']); ?></p>
                                <p class="card-text"><?= htmlspecialchars($servicio['precio']); ?></p>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo "Servicios no disponibles";
                }
                ?>
            </div>
            <div class="editar">
                <a href="insertar_servicios.php">Editar Servicios</a>
            </div>

            <!-- Editar Profesionales -->
            <h1 class="titulo">Profesionales</h1>
            <div class="card-deck">
                <?php
                $queryProfesionales = "SELECT * FROM profesionales
                                    WHERE estado != 'baja' AND id_profesionales IN (1, 2, 3)";
                $resultProfesionales = $conn->query($queryProfesionales);

                if ($resultProfesionales && $resultProfesionales->num_rows > 0) {
                    while ($profesional = $resultProfesionales->fetch_assoc()) {
                        ?>
                        <div class="card">
                            <h1 class="card-title"><?= htmlspecialchars($profesional['nombre']); ?></h1>
                            <img src="<?= htmlspecialchars($profesional['foto']); ?>" class="card-img-top" alt="<?= htmlspecialchars($profesional['nombre']); ?>">
                            <div class="card-body">
                                <p class="card-text"><?= htmlspecialchars($profesional['especialidad']); ?></p>
                                <p class="card-text"><?= htmlspecialchars($profesional['descripcion']); ?></p>
                                <p class="card-text"><?= htmlspecialchars($profesional['telefono']); ?></p>
                                <p class="card-text"><?= htmlspecialchars($profesional['email']); ?></p>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo "No existen profesionales";
                }
                ?>
            </div>
            <div class="editar">
                <a href="insertar_profesionales.php">Editar Profesionales</a>
            </div>
        </div>
    </body>
</html>
<?php include("footer/footer.php"); ?>
