<?php
    session_start();

    // Incluye la configuración de la base de datos
    include_once("config.php");

    if ($_SERVER['HTTP_HOST'] == 'localhost') {
        define('BASE_URL', 'http://localhost/ClinicaVitalMente/');
    } else {
        define('BASE_URL', 'https://vitalmenteclinica.es/');
    }

    if (isset($_POST['usuario'], $_POST['password'])) {
        $usuario = $_POST['usuario'];
        $contrasenia = $_POST['password'];

        // Limpiar las variables
        $usuario = trim(htmlspecialchars($usuario));
        $contrasenia = htmlspecialchars($contrasenia);

        // Convertir el usuario a minúscula
        $usuario = strtolower($usuario);

        // Validar el correo electrónico
        if (!filter_var($usuario, FILTER_VALIDATE_EMAIL)) {
            $mensaje = "El correo electrónico no es válido";
            $tipo = "alert-danger";
            header("Location: acceder.php?mensaje=" . urlencode($mensaje) . "&tipo=" . urlencode($tipo));
            exit();
        } else {
            // Verificar si está configurada la conexión a la bbdd
            if (!isset($conn)) {
                die("Error: No se pudo establecer conexión con la base de datos.");
            }
        }

        // Preparar la consulta para obtener datos de usuario y profesional (si aplica)
        $query = "SELECT usuarios.id_usuarios, usuarios.nombre, usuarios.email, usuarios.contrasena, usuarios.tipo_usuario, profesionales.id_profesionales
                FROM usuarios 
                LEFT JOIN profesionales 
                ON usuarios.id_usuarios = profesionales.id_usuario
                WHERE usuarios.email = ?";
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            die("Error en la consulta: " . $conn->error);
        }
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $almacenado = $stmt->get_result();

        if ($almacenado->num_rows > 0) {
            $login = $almacenado->fetch_assoc();

            // Verificar la contraseña
            if (password_verify($contrasenia, $login['contrasena'])) {
                // Configurar variables de sesión
                $_SESSION['id_usuarios'] = $login['id_usuarios'];
                $_SESSION['email'] = $login['email'];
                $_SESSION['tipo_usuario'] = $login['tipo_usuario'];

                // Si es profesional, asignar el id_profesionales
                if ($login['tipo_usuario'] === 'profesional') {
                    $_SESSION['id_profesional'] = $login['id_profesionales'];
                }

                // Redirigir según el tipo de usuario
                switch ($login['tipo_usuario']) {
                    case 'administrador':
                        header('Location:' . BASE_URL . 'admin.php');
                        break;
                    case 'profesional':
                        header('Location:' . BASE_URL . 'profesionales.php');
                        break;
                    default:
                        header('Location:' . BASE_URL . 'pacientes.php');
                        break;
                }
                exit();
            } else {
                $mensaje = "Contraseña incorrecta";
                $tipo = "alert-danger";
                header("Location: acceder.php?mensaje=" . urlencode($mensaje) . "&tipo=" . urlencode($tipo));
                exit();
            }
        } else {
            $mensaje = "Usuario no encontrado";
            $tipo = "alert-danger";
            header("Location: acceder.php?mensaje=" . urlencode($mensaje) . "&tipo=" . urlencode($tipo));
            exit();
        }
        $stmt->close();
    } else {
        $mensaje = "Todos los campos son obligatorios";
        $tipo = "alert-danger";
        header("Location: acceder.php?mensaje=" . urlencode($mensaje) . "&tipo=" . urlencode($tipo));
        exit();
    }
?>
