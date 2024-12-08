<?php
    // Verifica si hay una sesión activa
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Incluye la configuración de la base de datos
    include_once('config.php');

    // Solo permite acceso a administradores
    if ($_SESSION['tipo_usuario'] !== 'administrador') {
        header("Location: acceder.php");
        exit();
    }

    if (isset($_POST['titulo'], $_POST['descripcion'], $_POST['fecha'], $_POST['hora_inicio'], $_POST['hora_fin'], $_POST['id_admin'])) {
        $titulo = $_POST['titulo'];
        $descripcion = $_POST['descripcion'];
        $fecha = $_POST['fecha'];
        $hora_inicio = $_POST['hora_inicio'];
        $hora_fin = $_POST['hora_fin'];
        $id_admin = (int)$_POST['id_admin'];

        // Limpiar campos
        $titulo = htmlspecialchars($titulo);
        $descripcion = htmlspecialchars($descripcion);

        // Validar campos vacíos
        if (empty($titulo) || empty($descripcion) || empty($fecha) || empty($hora_inicio)|| empty($hora_fin) || $id_admin <= 0) {
            $_SESSION['mensaje'] = "Todos los campos son obligatorios";
            $_SESSION['tipo'] = "alert-danger";
            header("Location: insertarActividadCalendario_formulario.php");
            exit();
        }

        try {
            $query = "INSERT INTO actividades (titulo, descripcion, fecha, hora_inicio, hora_fin, id_admin) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
                if (!$stmt) {
                    throw new Exception("Error al preparar la consulta: " . $conn->error);
                }
            $stmt->bind_param('sssssi', $titulo, $descripcion, $fecha, $hora_inicio, $hora_fin, $id_admin);
                if ($stmt->execute()) {
                    $_SESSION['mensaje'] = "Actividad agregada correctamente";
                    $_SESSION['tipo'] = "alert-success";
                } else {
                    throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
                }

            $stmt->close();
        }catch (Exception $e) {
            $_SESSION['mensaje'] = "Error: " . $e->getMessage();
            $_SESSION['tipo'] = "alert-danger";
        }
        $conn->close();
    }else {
        $_SESSION['mensaje'] = "Error Todos los campos son obligatorios";
        $_SESSION['tipo'] = "alert-danger";
    }
    header("Location: insertarActividadCalendario_formulario.php");
    exit();
?>
