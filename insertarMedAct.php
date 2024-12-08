<?php
    // Verifica si hay una sesión activa.
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Configuración de conexión con la base de datos.
    include_once("config.php");

    // Verifica que el usuario esté autenticado como profesional.
    if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] !== 'profesional') {
        header("Location: acceder.php");
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id_citas = $_POST['id_citas'];
        $nombre_medicamento = $_POST['nombre_medicamento'];
        $dosis = $_POST['dosis'];
        $instrucciones = $_POST['instrucciones'];
        $actividad = $_POST['actividad'];

        //Limpiar campos
        $id_citas = htmlspecialchars($id_citas);
        $nombre_medicamento = htmlspecialchars($nombre_medicamento);
        $dosis = htmlspecialchars($dosis);
        $instrucciones = htmlspecialchars($instrucciones);
        $actividad = htmlspecialchars($actividad);

        // Insertar medicamento
        $query_medicamento = "INSERT INTO medicamentos_citas (id_citas, nombre_medicamento, dosis, instrucciones) VALUES (?, ?, ?, ?)";
        if ($stmt = $conn->prepare($query_medicamento)) {
            $stmt->bind_param('isss', $id_citas, $nombre_medicamento, $dosis, $instrucciones);
            $stmt->execute();
            $stmt->close();
        } else {
            echo "Error al agregar medicamento: " . $conn->error;
        }

        $query_actividad = "INSERT INTO actividades_citas (id_citas, descripcion) VALUES (?, ?)";
        if ($stmt = $conn->prepare($query_actividad)) {
            $stmt->bind_param('is', $id_citas, $actividad);
            $stmt->execute();
            $stmt->close();
        } else {
            echo "Error al agregar actividad: " . $conn->error;
        }

        $_SESSION['mensaje'] = "Medicamento y actividad agregados correctamente.";
        $_SESSION['tipo'] = "alert-success";
    }
    header("Location: medicamentoActividades.php");
    exit();
?>
