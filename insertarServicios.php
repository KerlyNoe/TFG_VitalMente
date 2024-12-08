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

    if(isset($_POST['nombre'], $_POST['imagen'], $_POST['descripcion'], $_POST['precio'])) {
        $nombre = $_POST['nombre'];
        $imagen = $_POST['imagen'];
        $descripcion = $_POST['descripcion'];
        $precio = $_POST['precio'];

        $asistencia = isset($_POST['asistencia']) ?  htmlspecialchars($_POST['asistencia']) : null;
       
        // Limpiar campos 
        $nombre = htmlspecialchars($nombre);
        $imagen = trim(htmlspecialchars($imagen));
        $descripcion = htmlspecialchars($descripcion);
        $precio = trim($precio);

        // Validar precio (debe ser numérico y mayor a 0)
        if (!is_numeric($precio) || $precio <= 0) {
            $mensaje = "El precio debe ser un número mayor a 0";
            $tipo = "alert-danger";
            header("Location: insertarServicios_formulario.php?mensaje=" . urlencode($mensaje) . "&tipo=" . urlencode($tipo));
            exit();
        }

        // Validar que no haya campos vacíos
        if (empty($nombre) || empty($imagen) || empty($descripcion)) {
            $mensaje = "Todos los campos son obligatorios.";
            $tipo = "alert-danger";
            header("Location: insertarServicios_formulario.php?mensaje=" . urlencode($mensaje) . "&tipo=" . urlencode($tipo));
            exit();
        }

        // Insertar el servicio en la base de datos
        $query = "INSERT INTO servicios (nombre_servicio, imagen, descripcion, asistencia, precio, estado) VALUES (?, ?, ?, ?, ?, 'disponible')";
        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param('ssssd', $nombre, $imagen, $descripcion, $asistencia, $precio);
            if ($stmt->execute()) {
                $mensaje = "Servicio agregado correctamente.";
                $tipo = "alert-success";
            } else {
                $mensaje = "Error al agregar el servicio.";
                $tipo = "alert-danger";
            }
            $stmt->close();
        }
    } else {
        $mensaje = "Campos incompletos.";
        $tipo = "alert-danger";
    }
    header("Location: insertarServicios_formulario.php?mensaje=" . urlencode($mensaje) . "&tipo=" . urlencode($tipo));
    exit();
?>
