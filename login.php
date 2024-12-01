<?php
    session_start();

    include_once("config.php");

    if (isset($_POST['usuario'], $_POST['password'])) {
        // Definir las variables
        $usuario = $_POST['usuario']; 
        $contrasenia = $_POST['password'];

        // Limpiar las variables
        $usuario = trim(htmlspecialchars($usuario));
        $contrasenia = htmlspecialchars($contrasenia);

        // Convertir el usuario a minúscula
        $usuario = strtolower($usuario);

        // Validad el correo electrónico
        if(!filter_var($usuario, FILTER_VALIDATE_EMAIL)) {
            $mensaje = "El correo electrónico no es válido";
            $tipo = "alert-danger";
            header("Location: acceder.php?mensaje=" . urlencode($mensaje) . "&tipo=" . urlencode($tipo));
            exit(); 
        }else {
            // Verificar si está confugurada la conexión a la bbdd
            if (!isset($conn)) {
                die("Error: No se pudo establecer conexión con la base de datos.");
            }
        }

        // Preparar la consulta
        $query = "SELECT id_usuarios, nombre, email, contrasena, tipo_usuario FROM usuarios 
                  WHERE email = ?";
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
                    $_SESSION['id_usuarios'] = $login['id_usuarios']; 
                    $_SESSION['email'] = $login['email'];
                    $_SESSION['tipo_usuario'] = $login['tipo_usuario'];

                    // Redirigir según el tipo de usuario
                    switch ($login['tipo_usuario']) {
                        case 'administrador':
                            header("Location: admin.php");
                            break;
                        case 'profesional':
                            header("Location: profesionales.php");
                            break;
                        default:
                            header("Location: pacientes.php");
                            break;
                    }
                    exit();
                } else {
                    $mensaje = "Contraseña incorrecta.";
                    $tipo = "alert-danger";
                    header("Location: acceder.php?mensaje=" . urlencode($mensaje) . "&tipo=" . urlencode($tipo));
                    exit();
                }
            } else {
                $mensaje = "Usuario no encontrado.";
                $tipo = "alert-danger";
                header("Location: acceder.php?mensaje=" . urlencode($mensaje) . "&tipo=" . urlencode($tipo));
                exit();
            }
        $stmt->close();
    }
?>
