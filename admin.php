<?php
    // Verifica si hay una sesión activa
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Incluye la configuración de la base de datos
    include_once("config.php");

    // Verifica si el usuario está autenticado y tiene que ser admin
    if (!isset($_SESSION['tipo_usuario'] || $_SESSION['tipo_usuario'] !== 'administrador' )) {
        header("Location: acceder.php");
        exit();
    }

    // Incluye el navegador
    include_once("header/navegadorPrimario.php");

    // Consulta las citas para los profesionales
    $queryCitas = "SELECT 
        citas.id_citas, 
        citas.fecha, 
        citas.hora_inicio,
        citas.hora_fin, 
        usuarios.nombre AS usuario_nombre, 
        profesionales_usuarios.nombre AS profesional_nombre
        FROM citas
        INNER JOIN usuarios 
        ON usuarios.id_usuarios = citas.id_usuarios
        INNER JOIN profesionales 
        ON citas.id_profesionales = profesionales.id_profesionales
        INNER JOIN usuarios AS profesionales_usuarios 
        ON profesionales.id_usuario = profesionales_usuarios.id_usuarios
        WHERE citas.estado = 'reservada'";

    $resultCitas = $conn->query($queryCitas);

    $eventos = [];
    $tablaCitas = [];

    if ($resultCitas && $resultCitas->num_rows > 0) {
        while ($cita = $resultCitas->fetch_assoc()) {
            $eventos[] = [
                'id' => $cita['id_citas'],
                'title' => $cita['usuario_nombre'] . ' con ' . $cita['profesional_nombre'],
                'start' => $cita['fecha'] . 'T' . $cita['hora_inicio'],
                'end' => $cita['fecha'] . 'T' . $cita['hora_fin'],
                'extendedProps' => [
                    'professional' => $cita['profesional_nombre'],
                    'fecha' => $cita['fecha'],
                    'hora_inicio' => $cita['hora_inicio'],
                    'hora_fin' => $cita['hora_fin'],
                ]
            ];
            $tablaCitas[] = $cita;
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
        <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.css" rel="stylesheet">
        <link rel="stylesheet" href="css/estilosAdmin.css">
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

        <div class="container-fluid p-4">
            <h1 class="titulo text-center">Calendario de Profesionales</h1>
            <div id="calendario" class="mb-4"></div>

            <!-- Parte de citas -->
            <h1 class="titulo text-center">Citas Agendadas</h1>
                <?php    
                    if (!empty($tablaCitas)) {
                        echo '<div class="table-responsive">';
                        echo '<table class="table table-striped">';
                        echo '<thead class="table-info">';
                        echo '<tr>';
                        echo '<th>PACIENTE</th>';
                        echo '<th>PROFESIONAL</th>';
                        echo '<th>FECHA</th>';
                        echo '<th>HORA INICIO</th>';
                        echo '<th>HORA FIN</th>';
                        echo '<th>AJUSTES</th>';
                        echo '</tr>';
                        echo '</thead>';
                        echo '<tbody>';
                        foreach ($tablaCitas as $cita) {
                            echo '<tr>';
                            echo '<td>' . htmlspecialchars($cita['usuario_nombre']) . '</td>';
                            echo '<td>' . htmlspecialchars($cita['profesional_nombre']) . '</td>';
                            echo '<td>' . htmlspecialchars($cita['fecha']) . '</td>';
                            echo '<td>' . htmlspecialchars($cita['hora_inicio']) . '</td>';
                            echo '<td>' . htmlspecialchars($cita['hora_fin']) . '</td>';
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
            
            <!-- Servicios -->
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
                                echo '<h1 class="card-title text-center">' . htmlspecialchars($servicio['nombre_servicio']) . '</h1>';
                                echo '<img src="img/servicios/' . htmlspecialchars($servicio['imagen']) . '" class="card-img-top" alt="' . htmlspecialchars($servicio['nombre_servicio']) . '">';
                                echo '<div class="card-body">';
                                echo '<p class="card-text">' . htmlspecialchars($servicio['descripcion']) . '</p>';
                                echo '<p class="card-text"><strong>Asistencia:</strong> ' . htmlspecialchars($servicio['asistencia']) . '</p>';
                                echo '<p class="card-text"><strong>Precio:</strong> ' . htmlspecialchars($servicio['precio']) . '€ P/S </p>';
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
                    <a href="insertarServicios_formulario.php" class="btn btn-primary">Añadir</a>
                    <a href="eliminarServicios_todos.php" class="btn btn-primary">Eliminar</a>
                </div>

                <!-- Profesionales -->
                <h1 class="titulo text-center">Profesionales</h1>
                <div class="row">
                <?php
                    $queryProfesionales = "SELECT usuarios.nombre, usuarios.primer_apellido, usuarios.segundo_apellido, usuarios.email, usuarios.telefono, profesionales.foto, profesionales.especialidad, profesionales.descripcion  
                                        FROM usuarios
                                        INNER JOIN profesionales 
                                        ON usuarios.id_usuarios = profesionales.id_usuario
                                        WHERE estado = 'activa' AND id_profesionales IN (1, 2, 3)";
                    $resultProfesionales = $conn->query($queryProfesionales);

                    if ($resultProfesionales && $resultProfesionales->num_rows > 0) {
                        while ($profesional = $resultProfesionales->fetch_assoc()) {
                            echo '<div class="col-md-4 mb-4">';
                                echo '<div class="card h-100">';
                                    echo '<h3 class="card-title text-center">'.  htmlspecialchars($profesional['nombre']) . '</h3>';
                                    echo '<img src="img/profesionales/' . htmlspecialchars($profesional['foto']) . '" class="card-img-top" alt="' .  htmlspecialchars($profesional['nombre']) . '">';
                                    echo '<div class="card-body">';
                                        echo '<p class="card-text"><strong>Especialidad:</strong>' . htmlspecialchars($profesional['especialidad']) . '</p>';
                                        echo '<p class="card-text">' . htmlspecialchars($profesional['descripcion']) . '</p>';
                                        echo '<p class="card-text"><strong>Teléfono:</strong>' .  htmlspecialchars($profesional['telefono']) . '</p>';
                                        echo '<p class="card-text"><strong>Email:</strong>' . htmlspecialchars($profesional['email']) .'</p>';
                                    echo'</div>';
                                echo '</div>';
                            echo '</div>';         
                        }
                    } else {
                        echo '<div class="alert alert-warning text-center">No existen profesionales disponibles.</div>';
                    }
                ?>

                </div>
                <div class="text-end mb-4">
                    <a href="insertarProfesionales_formulario.php" class="btn btn-primary">Añadir</a>
                    <a href="eliminarProfesionales_todos.php" class="btn btn-primary">Eliminar</a>
                </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="reservaModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalLabel">Detalles de la Reserva</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="modal-body">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

        <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                let calendario = new FullCalendar.Calendar(document.getElementById('calendario'), {
                    initialView: 'dayGridMonth',
                    events: <?php echo json_encode($eventos); ?>,
                    locale: 'es',
                    firstDay: 1,
                    eventColor: '#28a745',
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
                        },
                    eventClick: function(info) {
                        // Extrae la información del evento
                        let profesional = info.event.extendedProps.professional;
                        let fecha = info.event.extendedProps.fecha;
                        let horaInicio = info.event.extendedProps.hora_inicio;
                        let horaFin = info.event.extendedProps.hora_fin;

                        // Llena el contenido del modal
                        let modalContent = `
                            <h5>Reserva de: ${profesional}</h5>
                            <p><strong>Fecha:</strong> ${fecha}</p>
                            <p><strong>Hora de inicio:</strong> ${horaInicio}</p>
                            <p><strong>Hora de fin:</strong> ${horaFin}</p>
                        `;

                        document.getElementById('modal-body').innerHTML = modalContent;

                        let modal = new bootstrap.Modal(document.getElementById('reservaModal'));
                        modal.show();
                    }
                });

                calendario.render();
            });
        </script>
    </body>
</html>
<?php include("footer/footer.php"); ?>
