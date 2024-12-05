<?php
    //Verificar si hay una sesión activa.
    if(session_status() === PHP_SESSION_NONE){
        session_start();
    }

    //Configuración de la conexion a la base de datos. 
    include_once("config.php");

    $tipo_usuario = $_SESSION['tipo_usuario'] ?? null;

    //Agregar el navegador.
    if($tipo_usuario === 'normal'){
        include_once("header/navegadorTres.php");
    }else {
        include_once("header/navegadorCuatro.php");
    }
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Clínica Vitalmente | Actividades</title>
        <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
        <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
        <link rel="stylesheet" href="css/estilosActividadesCalendario.css">
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
    </head>
    <body>
        <!-- Contenedor principal -->
        <div class="container">
            <h1 class="page-title text-center">Actividades de Clínica Vitalmente</h1>
            
            <div class="calendar-container">
                <div id="calendar"></div>
            </div>

            <!-- Modal para mostrar los detalles de la actividad -->
            <div class="modal fade" id="activityModal" tabindex="-1" aria-labelledby="activityModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="activityModalLabel">Detalles de la Actividad</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p><strong>Título:</strong> <span id="activityTitle"></span></p>
                            <p><strong>Fecha y Hora:</strong> <span id="activityDate"></span></p>
                            <p><strong>Descripción:</strong></p>
                            <p id="activityDescription"></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Script para el calendario -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                let calendario = document.getElementById('calendar');
                let calendar = new FullCalendar.Calendar(calendario, {
                    initialView: 'dayGridMonth',
                    locale: 'es',
                    firstDay: 1,
                    events: './mostrarActividades.php',
                    eventColor: '#e4e9f9',
                    businessHours: {
                        daysOfWeek: [1, 2, 3, 4, 5, 6], 
                        startTime: '10:00', 
                        endTime: '18:00' 
                    },
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
                    contentHeight: 'auto',

                     // Evento para cuando el usuario hace clic en un evento
                    eventClick: function(info) {
                        // Mostrar los detalles en el modal
                        document.getElementById('activityTitle').innerText = info.event.title;
                        document.getElementById('activityDate').innerText = info.event.start.toLocaleString(); // Puedes ajustar el formato según lo necesites
                        document.getElementById('activityDescription').innerText = info.event.extendedProps.description; // Descripción que se pasa desde la base de datos
                        
                        // Mostrar el modal
                        var myModal = new bootstrap.Modal(document.getElementById('activityModal'));
                        myModal.show();
                    }
                });
                calendar.render();
            });
        </script>
    </body>
</html>
<?php include_once("footer/footer.php"); ?>
