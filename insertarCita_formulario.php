<?php
    // Verifica si hay una sesión activa
    if(session_status() === PHP_SESSION_NONE){
        session_start();
    }
    
    // Incluye la configuración de la base de datos
    include_once('config.php');

    // Verifica si el usuario está autenticado
    if (!isset($_SESSION['tipo_usuario'] || $_SESSION['tipo_usuario'] !== 'normal')) {
        header("Location: acceder.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title> Clínica VitalMente | Reservar Cita</title>
        <link rel="stylesheet" href="css/insertarCita.css">
        <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
        <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.css" rel="stylesheet">
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

        <div class="container mt-4">
            <h2 class="text-center text-danger mb-4">Reservar Cita</h2>

            <form action="insertarCita.php" method="POST">
                <input type="hidden" name="id_servicios" id="id_servicios" value="<?= htmlspecialchars($_POST['id_servicios'] ?? '') ?>">

                <!-- Fecha y Calendario -->
                <div class="text-center mb-4">
                    <h3 class="text-info">Selecciona una Fecha</h3>
                    <input type="text" name="fecha" id="fecha" class="form-control w-50 mx-auto mb-3" required>
                    <div id="calendario" class="mb-4"></div>
                </div>

                <!-- Motivo y Profesional -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="motivo" class="form-label text-info">Motivo</label>
                        <textarea name="motivo" id="motivo" class="form-control" rows="5" placeholder="Describe brevemente el motivo de la consulta" required></textarea>
                    </div>
                    <div class="col-md-6">
                        <label for="profesionales" class="form-label text-info">Profesional</label>
                        <select name="profesionales" id="profesionales" class="form-select" required>
                            <option value="">Selecciona un profesional</option>
                            <?php
                            $query_profesionales = "SELECT profesionales.id_profesionales, usuarios.nombre
                                                    FROM profesionales
                                                    LEFT JOIN usuarios
                                                    ON profesionales.id_usuario = usuarios.id_usuarios";
                            $stmt_profesionales = $conn->query($query_profesionales);

                            if ($stmt_profesionales) {
                                while ($row = $stmt_profesionales->fetch_assoc()) {
                                    echo "<option value='" . htmlspecialchars($row['id_profesionales']) . "'>" . htmlspecialchars(strtoupper($row['nombre'])) . "</option>";
                                }
                            } else {
                                echo "<option value=''>Error en la consulta</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <!-- Bóton para ver los horarios -->
                <div class="text-center mb-4">
                    <button type="button" id="verHorario" class="btn btn-danger w-50">Ver horarios disponibles</button>
                </div>

                <!-- Horarios Disponibles -->
                <div class="text-center mb-4">
                    <h3 class="text-danger">Horas Disponibles</h3>
                    <table class="table table-striped w-75 mx-auto">
                        <thead>
                            <tr>
                                <th>Hora</th>
                                <th>Estado</th>
                                <th>Seleccionar</th>
                            </tr>
                        </thead>
                        <tbody id="tabla-horarios">
                            <tr>
                                <td colspan="3" class="text-center">Selecciona una fecha para ver horarios.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Horas -->
                <input type="hidden" name="hora_inicio" id="hora_inicio" value="">
                <input type="hidden" name="hora_fin" id="hora_fin" value="">


                <!-- Botón -->
                <button type="submit" class="btn btn-success w-100">Confirmar</button>
            </form>
            <a href="pacientes.php" class="btn btn-secondary mt-3">Regresar</a>
        </div>

        <!-- FullCalendar -->
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.js"></script>
        <script>
          document.addEventListener('DOMContentLoaded', function () {
            const calendario = new FullCalendar.Calendar(document.getElementById('calendario'), {
                initialView: 'dayGridMonth',
                locale: 'es',
                firstDay: 1, // Comienza en lunes.
                businessHours: { daysOfWeek: [1, 2, 3, 4, 5, 6] }, 
                headerToolbar: {
                    left: 'prev,next today', 
                    center: 'title',
                    right: 'dayGridMonth' 
                },
                buttonText: { today: 'Hoy', month: 'Mes' },
                validRange: function (nowDate) {
                    return {
                        start: nowDate.toISOString(), /
                        end: null 
                    };
                },
                dateClick: function (info) {
                    const selectedDate = new Date(info.date);
                    const today = new Date();
                    today.setHours(0, 0, 0, 0);

                    if (selectedDate.getDay() === 0) {
                        alert('No puedes seleccionar un domingo.');
                        return;
                    }

                    if (selectedDate >= today) {
                        document.getElementById('fecha').value = info.dateStr; 
                    } else {
                        alert('No puedes seleccionar una fecha anterior al día actual.');
                    }
                },
            });

            calendario.render();

                // Cargar horarios disponibles
                document.getElementById('verHorario').addEventListener('click', function () {
                    const fecha = document.getElementById('fecha').value;
                    const idProfesional = document.getElementById('profesionales').value;

                    if (!fecha || !idProfesional) {
                        alert('Por favor selecciona una fecha y un profesional.');
                        return;
                    }

                    fetch(`obtener_horarios.php?fecha=${fecha}&id_profesional=${idProfesional}`)
                        .then(response => response.text())
                        .then(data => {
                            document.getElementById('tabla-horarios').innerHTML = data;
                        })
                        .catch(error => console.error('Error al cargar horarios:', error));
                });

                // Seleccionar la hora desde la tabla de horarios
                 document.getElementById('tabla-horarios').addEventListener('click', function (event) {
                    if (event.target && event.target.matches('input[type="radio"]')) {
                        const horaInicio = event.target.getAttribute('data-horaInicio');
                        const horaFin = event.target.getAttribute('data-horaFin');

                        if (horaInicio && horaFin) {
                            document.getElementById('hora_inicio').value = horaInicio;
                            document.getElementById('hora_fin').value = horaFin;
                        } else {
                            alert('Error: Los valores de hora no son válidos.');
                        }
                    }
                });
            });
        </script>
    </body>
</html>
