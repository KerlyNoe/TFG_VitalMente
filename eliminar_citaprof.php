<?php
    // Verifica si hay una sesión activa.
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Configuración de conexión con la base de datos
    include_once("config.php");

    // Verifica que el usuario esté autenticado como profesional
    if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] !== 'profesional') {
        header("Location: acceder.php");
        exit();
    }

    // Verifica que se haya recibido el ID de la cita
    if (isset($_POST['id_citas'])) {
        $id_cita = $_POST['id_citas'];
        $id_profesional = $_SESSION['id_usuarios']; 

        $query = "UPDATE citas SET estado = 'cancelada' WHERE id_citas = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $id_cita);
            if ($stmt->execute()) {
                $_SESSION['mensaje'] = "Cita cancelada con éxito.";
                $_SESSION['tipo'] = "alert-success";  
            } else {
                $_SESSION['mensaje'] = "Error al cancelar la cita.";
                $_SESSION['tipo'] = "alert-danger"; 
            }
        $stmt->close();
    } else {
        $_SESSION['mensaje'] = "No se ha proporcionado un ID de cita.";
        $_SESSION['tipo'] = "alert-danger"; 
    }

    // Redirige a la página de editar citas
    header("Location: editar_citas.php");
    exit();
?>
