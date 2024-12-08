<?php
    // Configuración de la base de datos
    include_once("config.php");

    $query = "SELECT * FROM actividades"; 
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        $actividades = [];
        while ($row = $result->fetch_assoc()) {
            $actividades[] = [
                'title' => $row['titulo'], 
                'start' => $row['fecha'] . 'T' . $row['hora_inicio'],
                'end' => $row['fecha'] . 'T' . $row['hora_fin'], 
                'description' => $row['descripcion'], 
            ];
        }
        // Devuelve las actividades en formato JSON
        echo json_encode($actividades);
    } else {
        echo json_encode([]);
    }
    $conn->close();
?>
