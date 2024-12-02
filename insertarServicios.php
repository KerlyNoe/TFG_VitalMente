<?php
    // Verifica si hay una sesión activa
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Incluye la configuración de la base de datos
    include_once("config.php");

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

    if(isset($_POST['nombre'], $_POST['imagen'], $_POST['descripcion'], $_POST['asistencia'], $_POST['precio'])) {
        $nombre = $_POST['nombre'];
        $imagen = $_POST['imagen'];
        $descripcion = $_POST['descripcion'];
        $asistencia = $_POST['asistencia'];
        $precio = $_POST['precio'];

        // Limpiar campos 
        $nombre = htmlspecialchars($nombre);
        $imagen = trim(htmlspecialchars($imagen));
        $descripcion = htmlspecialchars($descripcion);
        $asistencia = htmlspecialchars($asistencia);
        $precio = trim(htmlspecialchars($precio));

        // Insertar Servicio
        $query = "INSERT INTO servicios (nombre, imagen, descripcion, asistencia, precio) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('sssss', $nombre, $imagen, $descripcion, $asistencia, $precio);
            if($stmt->execute()) {
                $mensaje = "Servicio agregado correctamente";
                $tipo = "alert-success";
                header("Location: insertarServicios_formulario.php?mensaje=" . urlencode($mensaje) . "&tipo=" . urlencode($tipo));
                exit();
            }else {
                $mensaje = "Error al agregar un servicio";
                $tipo = "alert-danger";
                header("Location: insertarServicios_formulario.php?mensaje=" . urlencode($mensaje) . "&tipo=" . urlencode($tipo));
                exit();
            }
        $stmt->close();
    }else {
        $mensaje = "Campos incompletos";
        $tipo = "alert-danger";
        header("Location: insertarServicios_formulario.php?mensaje=" . urlencode($mensaje) . "&tipo=" . urlencode($tipo));
        exit();
    }



?>