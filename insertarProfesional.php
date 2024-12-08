<?php
    // Verifica si hay una sesión activa
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Incluye la configuración de la base de datos
    include_once("config.php");

    // Verifica si el usuario está autenticado
    if (!isset($_SESSION['tipo_usuario'] || $_SESSION['tipo_usuario'] !== 'administrador')) {
        header("Location: acceder.php");
        exit();
    }

    if(isset($_POST['id'], $_POST['imagen'], $_POST['especialidad'], $_POST['descripcion'])) {
        $id_usuario = $_POST['id'];
        $imagen = $_POST['imagen'];
        $especialidad = $_POST['especialidad'];
        $descripcion = $_POST['descripcion'];

        // Limpiar campos
        $id_usuario = trim(htmlspecialchars($id_usuario));
        $imagen = trim(htmlspecialchars($imagen));
        $especialidad = htmlspecialchars($especialidad);
        $descripcion = htmlspecialchars($descripcion);

        // Insertar Profesional
        $query = "INSERT INTO profesionales (id_usuario, foto, especialidad, descripcion, estado) VALUES (?,?,?,?, 'activa')";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('isss', $id_usuario, $imagen, $especialidad, $descripcion);
            if($stmt->execute()) {
                $mensaje = "Profesional agregado correctamente";
                $tipo = "alert-success";
            }else {
                $mensaje = "Error al agregar al profesional";
                $tipo = "alert-danger";
            }
        $stmt->close();
    }else {
        $mensaje = "Campos incompletos";
        $tipo = "alert-danger";
    }
    header("Location: insertarProfesionales_formulario.php?mensaje=" . urlencode($mensaje) . "&tipo=" . urlencode($tipo));
    exit();
?>
