<?php
    // Verifica si hay una sesión activa
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Incluye la configuración de la base de datos
    include_once("config.php");

    // Verifica si el usuario está autenticado y es administrador
    if (!isset($_SESSION['tipo_usuario'] || $_SESSION['tipo_usuario'] != 'administrador')) {
        header("Location: acceder.php");
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_citas'])) {
        $id_citas = $_POST['id_citas'];

      
        $queryActualizar = "UPDATE citas SET estado = 'cancelada' WHERE id_citas = ?";
        $stmt = $conn->prepare($queryActualizar);
        $stmt->bind_param('i', $id_citas);
        if ($stmt->execute()) {
            $_SESSION['mensaje'] = "La cita fue cancelada exitosamente.";
            $_SESSION['tipo'] = "alert-success";
        } else {
            $_SESSION['mensaje'] = "Error al cancelar la cita: " . $conn->error;
            $_SESSION['tipo'] = "alert-danger";
        }
    } else {
        $_SESSION['mensaje'] = "ID de cita no válido.";
        $_SESSION['tipo'] = "alert-danger";
    }
    header("Location: admin.php");
    exit();
?>