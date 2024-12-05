<?php
// Inicia sesión si no está activa.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Configuración de conexión con la base de datos.
include_once("config.php");

// Verifica que el usuario esté autenticado y tenga el rol de profesional.
if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] !== 'profesional') {
    header("Location: acceder.php");
    exit();
}

// Obtiene el id del profesional desde la sesión.
$id_profesional = $_SESSION['id_profesional'] ?? null;

// Si no se encuentra el id del profesional, redirige.
if ($id_profesional === null) {
    echo '<p class="text-danger">No se encontró información del profesional.</p>';
    exit();
}

include_once("header/navegadorPrimario.php");
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Clínica VitalMente | Profesionales</title>
        <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
        <script src="bootstrap/js/bootstrap.bundle.min.js"></script>  
        <!-- FULLCALENDAR -->
        <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.css" rel="stylesheet">
        <link rel="stylesheet" href="css/estilosProfesionales.css">
    </head>
    <body>
        <div class="container my-5">
            <!-- Información del profesional -->
            <div class="col-md-12">
                <div class="d-flex align-items-center">
                    <?php
                        // Consulta para obtener la foto y nombre del profesional.
                        $query = "SELECT profesionales.foto, usuarios.nombre, usuarios.primer_apellido
                                FROM profesionales 
                                INNER JOIN usuarios ON profesionales.id_usuario = usuarios.id_usuarios
                                WHERE id_profesionales = ?";
                        
                        if ($stmt = $conn->prepare($query)) {
                            $stmt->bind_param('i', $id_profesional);
                            $stmt->execute();
                            $profesional = $stmt->get_result();

                            if ($profesional->num_rows > 0) {
                                $profesional_info = $profesional->fetch_assoc();
                                echo '<img src="' . htmlspecialchars("img/profesionales/" . $profesional_info['foto']) . '" class="img-fluid rounded-circle me-3" alt="Foto de ' . htmlspecialchars($profesional_info['nombre']) . '" style="width: 120px; height: 120px;">';
                                echo '<h2 class="text-secondary mb-0">' . htmlspecialchars($profesional_info['nombre']). ' ' . htmlspecialchars($profesional_info['primer_apellido']) . '</h2>';
                            } else {
                                echo '<p class="text-danger">No se encontró información del profesional.</p>';
                            }
                            $stmt->close();
                        }
                    ?>
                </div>
            </div>

            <!-- Citas pendientes -->
            <h3 class="text-center mb-4">Citas Pendientes</h3>
            <div id="calendar" class="mb-5"></div>
            <a href="#" class="btn btn-primary d-block mx-auto mb-4" style="max-width: 200px;">Editar Cita</a>

            <!-- Tabla de pacientes -->
            <h3 class="text-center mb-4">Pacientes</h3>
            <?php
            // Consulta para obtener las citas pendientes y los pacientes
            $query = "SELECT 
                    usuarios.nombre AS paciente, 
                    servicios.nombre_servicio AS servicio, 
                    citas.fecha AS fecha_cita, 
                    medicamentos_citas.nombre_medicamento AS medicamento, 
                    medicamentos_citas.dosis AS dosis, 
                    medicamentos_citas.instrucciones AS instrucciones, 
                    actividades_citas.descripcion AS descripcion
                    FROM usuarios
                    INNER JOIN citas ON usuarios.id_usuarios = citas.id_usuarios
                    INNER JOIN servicios ON citas.id_servicios = servicios.id_servicios
                    LEFT JOIN medicamentos_citas ON citas.id_citas = medicamentos_citas.id_citas
                    LEFT JOIN actividades_citas ON citas.id_citas = actividades_citas.id_citas
                    WHERE citas.id_profesionales = ?";

            if ($stmt = $conn->prepare($query)) {
                $stmt->bind_param('i', $id_profesional);
                $stmt->execute();
                $resultado = $stmt->get_result();

                if ($resultado->num_rows > 0) {
                    echo '<div class="table-responsive">';
                    echo '<table class="table table-striped table-bordered">';
                    echo '<thead class="table-primary">';
                    echo '<tr>
                            <th>Paciente</th>
                            <th>Servicio</th>
                            <th>Fecha de Cita</th>
                            <th>Medicamentos</th>
                            <th>Actividades</th>
                        </tr>';
                    echo '</thead>';
                    echo '<tbody>';
                    while ($fila = $resultado->fetch_assoc()) {
                        echo '<tr>
                                <td>' . htmlspecialchars($fila['paciente']) . '</td>
                                <td>' . htmlspecialchars($fila['servicio']) . '</td>
                                <td>' . htmlspecialchars($fila['fecha_cita']) . '</td>
                                <td>' . htmlspecialchars($fila['medicamento']) . '<br>' 
                                    . htmlspecialchars($fila['dosis']) . '<br>' 
                                    . htmlspecialchars($fila['instrucciones']) . '</td>
                                <td>' . htmlspecialchars($fila['descripcion']) . '</td>
                            </tr>';
                    }
                    echo '</tbody>';
                    echo '</table>';
                    echo '</div>';
                } else {
                    echo '<p class="text-center text-muted">No hay pacientes registrados.</p>';
                }
                $stmt->close();
            }
            ?>
            <a href="#" class="btn btn-success d-block mx-auto mt-4" style="max-width: 250px;">Agregar Medicamentos y Actividades</a>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                let calendarEl = document.getElementById('calendar');
                let calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    locale: 'es',
                    firstDay: 1,
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay'
                    },
                    buttonText: {
                        today: 'Hoy',
                        month: 'Mes',
                        week: 'Semana',
                        day: 'Día'
                    },
                    events: 'mostrarReservas.php',
                    eventColor: '#0d6efd', 
                    eventTextColor: '#ffffff', 
                    height: 'auto', 
                    aspectRatio: 1.5, 
                    contentHeight: 'auto',
                    windowResize: function () {
                        calendar.updateSize(); 
                    },
                    editable: false, 
                    dayMaxEvents: true, 
                    eventClick: function (info) {
                        info.jsEvent.preventDefault();
                        alert(`Reserva: ${info.event.title}\nFecha: ${info.event.start.toLocaleDateString()}`);
                    }
                });

                calendar.render();
            });
        </script>
    </body>
</html>
<?php include("footer/footer.php"); ?>
