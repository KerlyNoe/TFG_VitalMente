<?php
    // Configuración de la base de datos
    include_once("config.php");

    // Realizar la consulta a la base de datos para obtener las actividades
    $query = "SELECT * FROM actividades"; // Ajusta la consulta según tu estructura de base de datos
    $result = $conn->query($query);

    // Verificar si la consulta fue exitosa
    if ($result->num_rows > 0) {
        $actividades = [];
        while ($row = $result->fetch_assoc()) {
            // Crear un arreglo para cada actividad en el formato que FullCalendar necesita
            $actividades[] = [
                'title' => $row['titulo'], // Título de la actividad
                'start' => $row['fecha'] . 'T' . $row['hora_inicio'], // Fecha y hora de inicio
                'end' => $row['fecha'] . 'T' . $row['hora_fin'], // Fecha y hora de fin
                'description' => $row['descripcion'], // Descripción de la actividad (opcional)
            ];
        }
        
        // Devolver las actividades en formato JSON
        echo json_encode($actividades);
    } else {
        echo json_encode([]);
    }

    // Cerrar la conexión a la base de datos
    $conn->close();
?>
