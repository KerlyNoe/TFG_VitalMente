<?php
    // Verifica si hay una sesión activa.
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Configuración de conexión con la base de datos.
    include_once("config.php");

    // Verifica que el usuario esté autenticado.
    if (!isset($_SESSION['id_usuarios'])) {
        header("Location: acceder.php");
        exit();
    }

    $id_usuario = $_SESSION['id_usuarios'];

    // Verifica que se haya enviado el formulario y que contenga un ID de cita.
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_citas'])) {
        $id_citas = $_POST['id_citas'];

        $query_verificar = "SELECT id_citas FROM citas 
                            WHERE id_citas = ? AND id_usuarios = ? 
                            AND estado = 'reservada'";
        if ($stmt = $conn->prepare($query_verificar)) {
            $stmt->bind_param('ii', $id_citas, $id_usuario);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows > 0) {
                $query_cancelar = "UPDATE citas SET estado = 'cancelada' WHERE id_citas = ?";
                if ($stmt_update = $conn->prepare($query_cancelar)) {
                    $stmt_update->bind_param('i', $id_citas);
                    if ($stmt_update->execute()) {
                        $_SESSION['mensaje'] = "La cita ha sido cancelada con éxito.";
                        $_SESSION['tipo'] = "alert-success";
                    } else {
                        $_SESSION['mensaje'] = "Ocurrió un error al cancelar la cita. Por favor, inténtalo nuevamente.";
                        $_SESSION['tipo'] = "alert-danger";
                    }
                    $stmt_update->close();
                } else {
                    $_SESSION['mensaje'] = "Error de consulta: " . $conn->error;
                    $_SESSION['tipo'] = "alert-danger";
                }
            } else {
                $_SESSION['mensaje'] = "No se encontró la cita";
                $_SESSION['tipo'] ="alert-danger";
            }
            $stmt->close();
        } else {
            $_SESSION['mensaje'] = "Error de consulta ";
            $_SESSION['tipo'] = "alert-danger";
        }
    } else {
        $_SESSION['mensaje'] = "No se recibió ninguna cita válida para cancelar.";
        $_SESSION['tipo'] = "alert-danger";
    }

    // Redirige a la página principal
    header("Location: pacientes.php");
    exit();
?>
