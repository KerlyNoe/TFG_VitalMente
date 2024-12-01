<?php 
    // Verifica si hay una sesión activa
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Incluye la configuración de la base de datos
    include_once("config.php");

    // Verifica si el usuario está autenticado
    if (!isset($_SESSION['tipo_usuario'])) {
        header("Location: acceder.php");
        exit();
    }

    // Solo permite acceso a administradores
    if ($_SESSION['tipo_usuario'] !== 'administrador') {
        header("Location: acceder.php");
        exit();
    }

    include_once("header/navegadorPrimario.php");

    // Consulta las citas para los profesionales
    $queryCitas = "SELECT 
                        citas.id_citas, 
                        citas.fecha, 
                        citas.hora, 
                        usuarios.nombre AS usuario_nombre, 
                        profesionales.nombre AS profesional_nombre 
                    FROM citas
                    INNER JOIN usuarios ON usuarios.id_usuarios = citas.id_usuarios
                    INNER JOIN profesionales ON citas.id_profesionales = profesionales.id_profesionales
                    WHERE citas.estado = 'reservada'";

    $resultCitas = $conn->query($queryCitas);
    $eventos = [];

    if ($resultCitas && $resultCitas->num_rows > 0) {
        while ($cita = $resultCitas->fetch_assoc()) {
            $fecha_hora = $cita['fecha'] . 'T' . $cita['hora']; 
            $eventos[] = [
                'title' => $cita['usuario_nombre'] . ' con ' . $cita['profesional_nombre'],
                'start' => $fecha_hora,
                'color' => '#28a745',
            ];
        }
    }
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Clínica VitalMente | Perfil</title>
        <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
        <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
        <!-- FULLCALENDAR -->
        <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.js"></script>
        <link rel="stylesheet" href="css/estilosAdmin.css">
    </head>
    <body>
        <div class="container-fluid p-4">
            <h1 class="titulo text-center">Calendario de Profesionales</h1>
            <div id="calendario" class="mb-4"></div>
            <!-- FullCalendar JS -->
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    var calendario = new FullCalendar.Calendar(document.getElementById('calendario'), {
                        initialView: 'dayGridMonth', 
                        events: <?php echo json_encode($eventos); ?>,
                        locale: 'es', 
                        firstDay: 1,
                        eventColor: '#28a745',
                        businessHours: {
                            daysOfWeek: [1, 2, 3, 4, 5],
                        },
                        headerToolbar: {
                            left: 'prev,next today',
                            center: 'title',
                            right: 'dayGridMonth,dayGridWeek,dayGridDay'
                        },
                        buttonText: {
                            today: 'Hoy',
                            month: 'Mes',
                            week: 'Semana',
                            day: 'Día'
                        }
                    });

                    calendario.render();
                });
            </script>
           <!-- Parte de citas -->
            <h1 class="titulo text-center">Citas Agendadas</h1>
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
                echo '<div class="table-responsive">';
                echo '<table class="table table-striped">';
                echo '<thead class="table-dark">';
                echo '<tr>';
                echo '<th>PACIENTE</th>';
                echo '<th>PROFESIONAL</th>';
                echo '<th>FECHA</th>';
                echo '<th>HORA</th>';
                echo '<th>PAGO</th>';
                echo '<th>AJUSTES</th>';
                echo '</tr>';
                echo '</thead>';
                echo '<tbody>';
                while ($cita = $resultCitas->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($cita['usuario_nombre']) . '</td>';
                    echo '<td>' . htmlspecialchars($cita['profesional_nombre']) . '</td>';
                    echo '<td>' . htmlspecialchars($cita['fecha']) . '</td>';
                    echo '<td>' . htmlspecialchars($cita['hora']) . '</td>';
                    echo '<td>' . htmlspecialchars($cita['estado_pago']) . '</td>';
                    echo '<td>';
                    echo '<form action="eliminar_citas.php" method="post" class="d-inline">';
                    echo '<input type="hidden" name="id_citas" value="' . htmlspecialchars($cita['id_citas']) . '">';
                    echo '<button type="submit" class="btn btn-danger btn-sm">Eliminar</button>';
                    echo '</form>';
                    echo '</td>';
                    echo '</tr>';
                }
                echo '</tbody>';
                echo '</table>';
                echo '</div>';
            } else {
                echo '<div class="alert alert-warning text-center">No existen citas agendadas</div>';
            }
            ?>

            <h1 class="titulo text-center">Servicios</h1>
            <div class="row">
                <?php
                $queryServicios = "SELECT * FROM servicios 
                                    WHERE estado != 'eliminado' 
                                    AND nombre_servicio IN ('Ansiedad','Terapia familiar','Adicciones')";
                $resultServicios = $conn->query($queryServicios);

                if ($resultServicios && $resultServicios->num_rows > 0) {
                    while ($servicio = $resultServicios->fetch_assoc()) {
                        echo '<div class="col-md-4 mb-4">';
                        echo '<div class="card h-100">';
                        echo '<img src="' . htmlspecialchars($servicio['imagen']) . '" class="card-img-top" alt="' . htmlspecialchars($servicio['nombre_servicio']) . '">';
                        echo '<div class="card-body">';
                        echo '<h5 class="card-title text-center">' . htmlspecialchars($servicio['nombre_servicio']) . '</h5>';
                        echo '<p class="card-text">' . htmlspecialchars($servicio['descripcion']) . '</p>';
                        echo '<p class="card-text"><strong>Asistencia:</strong> ' . htmlspecialchars($servicio['asistencia']) . '</p>';
                        echo '<p class="card-text"><strong>Precio:</strong> ' . htmlspecialchars($servicio['precio']) . '</p>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo '<div class="alert alert-warning text-center">Servicios no disponibles</div>';
                }
                ?>
            </div>
            <div class="text-end mb-4">
                <a href="insertar_servicios.php" class="btn btn-primary">Editar Servicios</a>
            </div>

            <h1 class="titulo text-center">Profesionales</h1>
            <div class="row">
                <?php
                $queryProfesionales = "SELECT * FROM profesionales
                                    WHERE estado != 'baja' AND id_profesionales IN (1, 2, 3)";
                $resultProfesionales = $conn->query($queryProfesionales);

                if ($resultProfesionales && $resultProfesionales->num_rows > 0) {
                    while ($profesional = $resultProfesionales->fetch_assoc()) {
                        echo '<div class="col-md-4 mb-4">';
                        echo '<div class="card h-100">';
                        echo '<img src="' . htmlspecialchars($profesional['foto']) . '" class="card-img-top" alt="' . htmlspecialchars($profesional['nombre']) . '">';
                        echo '<div class="card-body">';
                        echo '<h5 class="card-title text-center">' . htmlspecialchars($profesional['nombre']) . '</h5>';
                        echo '<p class="card-text"><strong>Especialidad:</strong> ' . htmlspecialchars($profesional['especialidad']) . '</p>';
                        echo '<p class="card-text">' . htmlspecialchars($profesional['descripcion']) . '</p>';
                        echo '<p class="card-text"><strong>Teléfono:</strong> ' . htmlspecialchars($profesional['telefono']) . '</p>';
                        echo '<p class="card-text"><strong>Email:</strong> ' . htmlspecialchars($profesional['email']) . '</p>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo '<div class="alert alert-warning text-center">No existen profesionales</div>';
                }
                ?>
            </div>
            <div class="text-end">
                <a href="insertar_profesionales.php" class="btn btn-primary">Editar Profesionales</a>
            </div>
        </div>
    </body>
</html>
<?php include("footer/footer.php"); ?>