<?php  include_once("header/NavegadorPrimario.php"); ?>
<!DOCTYPE html>
<html lan="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Clínica Vitalmente | Actividades</title>
        <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
        <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
        <link rel="stylesheet" href="css/estilosActividades.css">
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
    </head>
    <body>
    <div id="calendar" style="height:55%;margin:70px;"></div>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    let calendario = document.getElementById('calendar');
                    let calendar = new FullCalendar.Calendar(calendario, {
                        initialView: 'dayGridMonth',
                        locale: 'es',
                        events: './mostrar_actividades.php',
                        eventColor: '#f40e0e',
                        businessHours: {
                            // Solo de lunes a viernes
                            daysOfWeek: [1, 2, 3, 4, 5], 
                            startTime: '10:00', 
                            endTime: '18:00' 
                        },
                    });
                        calendar.render();
                });
            </script>
        
    </body>
</html>
<?php  include_once("footer/footer.php"); ?>
