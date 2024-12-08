<?php
    // Verifica si hay una sesión activa
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Incluye la configuración de la base de datos
    include_once('config.php');

    // Verifica si el usuario está autenticado
    if (!isset($_SESSION['tipo_usuario'] || $_SESSION['tipo_usuario'] !== 'normal')) {
        header("Location: acceder.php");
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id_servicios = $_POST['id_servicios'];
        $fecha = $_POST['fecha'];
        $motivo = $_POST['motivo'];
        $id_profesionales = $_POST['profesionales'];
        $hora_inicio = $_POST['hora_inicio'];
        $hora_fin = $_POST['hora_fin'];
        $id_usuario = $_SESSION['id_usuarios'];

        // Validar que todos los campos están completos
        if (empty($fecha) || empty($motivo) || empty($id_profesionales) || empty($hora_inicio) || empty($hora_fin)) {
            $_SESSION['mensaje'] = 'Todos los campos son obligatorios.';
            $_SESSION['tipo'] = 'alert-danger';
            header('Location: insertarCita_formulario.php');
            exit();
        }

        // Limpiar campos
        $fecha = htmlspecialchars($fecha);
        $motivo = htmlspecialchars($motivo);
        $hora_inicio = htmlspecialchars($hora_inicio);
        $hora_fin = htmlspecialchars($hora_fin);

        $query = "INSERT INTO citas (id_usuarios,id_profesionales, id_servicios, fecha, motivo, hora_inicio, hora_fin, estado)
                  VALUES (?, ?, ?, ?, ?, ?, ?, 'reservada')";
        $stmt= $conn->prepare($query);
        $stmt->bind_param('iiissss', $id_usuario, $id_profesionales, $id_servicios, $fecha, $motivo, $hora_inicio, $hora_fin);
        if ($stmt->execute()) {
            $_SESSION['mensaje'] = 'Cita reservada con éxito.';
            $_SESSION['tipo'] = 'alert-success';
        } else {
            $_SESSION['mensaje'] = 'Error al reservar la cita: ' . $conn->error;
            $_SESSION['tipo'] = 'alert-danger';
        }
        $stmt->close();
    }
    header('Location: insertarCita_formulario.php');
    exit();
?>
