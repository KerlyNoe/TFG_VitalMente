<?php
    // Verifica si hay una sesión activa
    if(session_status() === PHP_SESSION_NONE){
        session_start();
    }
    
    // Incluye la configuración de la base de datos
    include_once('config.php');

    // Verifica si el usuario está autenticado
    if (!isset($_SESSION['tipo_usuario'] || $_SESSION['tipo_usuario'] !== 'administrador')) {
        header("Location: acceder.php");
        exit();
    }

    if (isset($_POST['id_servicios'])) {
        $id_servicio = $_POST['id_servicios'];

        $query = "UPDATE servicios SET estado = 'eliminado' 
                  WHERE id_servicios = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $id_servicio);
        if ($stmt->execute()) {
            $mensaje = "El servicio se ha eliminado correctamente";
            $tipo = "alert-success";
            header("Location: eliminarServicios_todos.php?mensaje=" . urlencode($mensaje) . "&tipo=" . urlencode($tipo));
            exit();
        } else {
            $mensaje = "Error al eliminar el servicio";
            $tipo = "alert-danger";
            header("Location: eliminarServicios_todos.php?mensaje=" . urlencode($mensaje) . "&tipo=" . urlencode($tipo));
            exit();
        }
        $stmt->close();
    } else {
        $mensaje = "El id de servicio no existe";
        $tipo = "alert-danger";
    }
    header("Location: eliminarServicios_todos.php?mensaje=" . urlencode($mensaje) . "&tipo=" . urlencode($tipo));
    exit();
?>
