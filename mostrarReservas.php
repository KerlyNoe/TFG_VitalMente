<?php
    include_once("config.php");

    session_start();
    if (!isset($_SESSION['id_profesional'])) {
        http_response_code(403);
        echo json_encode(["error" => "No autorizado"]);
        exit();
    }

    $id_profesional = $_SESSION['id_profesional'];

    $query = "
        SELECT 
            citas.id_citas AS id,
            CONCAT(usuarios.nombre, ' - ', servicios.nombre_servicio) AS title,
            citas.fecha AS start
        FROM citas
        INNER JOIN usuarios ON citas.id_usuarios = usuarios.id_usuarios
        INNER JOIN servicios ON citas.id_servicios = servicios.id_servicios
        WHERE citas.id_profesionales = ?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $id_profesional);
    $stmt->execute();
    $resultado = $stmt->get_result();

    $eventos = [];
    while ($fila = $resultado->fetch_assoc()) {
        $eventos[] = [
            "id" => $fila["id"],
            "title" => $fila["title"],
            "start" => $fila["start"]
        ];
    }

    $stmt->close();
    $conn->close();

    header('Content-Type: application/json');
    echo json_encode($eventos);
?>