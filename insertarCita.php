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

    // Solo permite acceso a usuarios normales
    if ($_SESSION['tipo_usuario'] !== 'normal') {
        header("Location: acceder.php");
        exit();
    }

    if(isset($_POST['profesionales'], $_POST['id_servicios'], $_POST['fecha'], $_POST['motivo'], $_POST['hora_inicio'], $_POST['hora_fin'])) {
        $id_usuario = $_SESSION['id_usuarios']; // El id del usuario lo obtienes de la sesión
        $id_profesional = $_POST['profesionales'];
        $id_servicio = $_POST['id_servicios'];
        $fecha = $_POST['fecha'];
        $motivo = $_POST['motivo'];
        $hora_inicio = $_POST['hora_inicio']; // Hora seleccionada en la tabla de horarios
        $hora_fin = $_POST['hora_fin']; // Hora seleccionada en la tabla de horarios

        // Insertar datos en la tabla de citas
        $query = "INSERT INTO citas (id_usuarios, id_profesionales, id_servicios, fecha, motivo, hora_inicio, hora_fin, estado)
                VALUES (?, ?, ?, ?, ?, ?, ?, 'reservada')";

        // Preparar la consulta
        if ($stmt = $conn->prepare($query)) {
            // Vincular los parámetros
            $stmt->bind_param("iiissss", $id_usuario, $id_profesional, $id_servicio, $fecha, $motivo, $hora_inicio, $hora_fin);

            // Ejecutar la consulta
            if ($stmt->execute()) {
                $_SESSION['mensaje'] =  "Cita realizada correctamete.";
                $_SESSION['tipo'] =  "alert-success";
            } else {
                $_SESSION['mensaje'] =  "Error al realizar la cita.";
                $_SESSION['tipo'] = "alert-danger";
            }

            // Cerrar la consulta
            $stmt->close();
        } else {
            $_SESSION['mensaje'] = "Error en la base de datos.";
            $_SESSION['tipo'] = "alert-danger";
        }
    }
     // Redirige con el mensaje final
     header("Location: insertarCita_formulario.php");
     exit();
?>
