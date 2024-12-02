<?php
    if(session_status() === PHP_SESSION_NONE){
        session_start();
    }
    
    // Incluye la configuración de la base de datos
    include_once('config.php');

    // Verifica si el usuario está autenticado
    if (!isset($_SESSION['tipo_usuario'])) {
        header("Location: acceder.php");
        exit();
    }

    // Solo permite acceso a administradores
    if ($_SESSION['tipo_usuario'] !== 'administrador') {
        header("Location: acceder.php");
        exit();
    }

    // Verifica si se recibió el ID del profesional
    if (isset($_POST['id_sprofesionales'])) {
        $id_profesional = $_POST['id_profesionales'];

        // Actualiza el estado del profesional a "baja
        $query = "UPDATE profesionales SET estado = 'baja' 
                  WHERE id_servicios = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $id_profesional);
        if ($stmt->execute()) {
            $mensaje = "El profesional se ha eliminado correctamente";
            $tipo = "alert-success";
            header("Location: eliminarProfesionales_todos.php?mensaje=" . urlencode($mensaje) . "&tipo=" . urlencode($tipo));
            exit();
        } else {
            // Redirige con un mensaje de error
            $mensaje = "Error al eliminar el profesional";
            $tipo = "alert-danger";
            header("Location: eliminarProfesionales_todos.php?mensaje=" . urlencode($mensaje) . "&tipo=" . urldecode($tipo));
            exit();
        }
        $stmt->close();
    } else {
        $mensaje = "El id de profesional no existe";
        $tipo = "alert-danger";
        header("Location: eliminarProfesionales_todos.php?mensaje=" . urlencode($mensaje) . "&tipo=" . urlencode($tipo));
        exit();
    }
?>
