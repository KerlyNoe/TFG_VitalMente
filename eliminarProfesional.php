<?php
    // Verifica si hay una sesión activa
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Incluye la configuración de la base de datos
    include_once('config.php');

    // Verifica si el usuario está autenticado
    if (!isset($_SESSION['tipo_usuario'] || $_SESSION['tipo_usuario'] !== 'administrador')) {
        header("Location: acceder.php");
        exit();
    }

    if (!empty($_POST['id_profesionales'])) {
        $id_profesional = $_POST['id_profesionales'];

        $query = "UPDATE profesionales SET estado = 'baja' WHERE id_profesionales = ?";
        $stmt = $conn->prepare($query);
        if ($stmt) {
            $stmt->bind_param('i', $id_profesional);
            if ($stmt->execute() && $stmt->affected_rows > 0) {
                $mensaje = "El profesional se ha eliminado correctamente.";
                $tipo = "alert-success";
            } else {
                $mensaje = "No se encontró un profesional con ese ID o ya estaba dado de baja.";
                $tipo = "alert-danger";
            }
            $stmt->close();
        } else {
            $mensaje = "Error al preparar la consulta: " . $conn->error;
            $tipo = "alert-danger";
        }
    } else {
        $mensaje = "El ID del profesional es inválido.";
        $tipo = "alert-danger";
    }

    header("Location: eliminarProfesionales_todos.php?mensaje=" . urlencode($mensaje) . "&tipo=" . urlencode($tipo));
    exit();
?>
