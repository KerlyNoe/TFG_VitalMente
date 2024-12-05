<?php
    include_once('config.php');

    if (isset($_POST['nombre'], $_POST['primer_apellido'], $_POST['mail'], $_POST['password'], $_POST['telefono'])) {
        // Definir las variables       
        $usuario = $_POST['nombre'];
        $primer_apellido = $_POST['primer_apellido'];
        $segundo_apellido = isset($_POST['segundo_apellido']) ? $_POST['segundo_apellido']: " ";
        $email = $_POST['mail'];
        $contrasenia = $_POST['password'];
        $telefono = $_POST['telefono'];

        // Validar que el nombre, primer apellido y segundo apellido no tengan caracteres especiales.
        if(!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/', $usuario)) {
            $mensaje = "El nombre no es válido";
            $tipo = "alert-danger";
            header("Location: registro.php?mensaje=" . urlencode($mensaje) . "&tipo=" . urlencode($tipo));
            exit();
        }

        if(!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/', $primer_apellido)) {
            $mensaje = "Primer apellido no válido";
            $tipo = "alert-danger";
            header("Location: registro.php?mensaje=" . urlencode($mensaje) . "&tipo=" . urlencode($tipo));
            exit();
        }

        if(!empty($segundo_apellido) && !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/', $segundo_apellido)) {
            $mensaje = "Segundo apellido no válido";
            $tipo = "alert-danger";
            header("Location: registro.php?mensaje=" . urlencode($mensaje) . "&tipo=" . urlencode($tipo));
            exit();
        }
        
        // Limpiar las variables
        $usuario = trim(htmlspecialchars($usuario));
        $primer_apellido = trim(htmlspecialchars($primer_apellido));
        $segundo_apellido = trim(htmlspecialchars($segundo_apellido));
        $email = trim(htmlspecialchars($email));
        $contrasenia = htmlspecialchars($contrasenia);
        $telefono = trim(htmlspecialchars($telefono));

        // Convertir los datos a minúscula
        $usuario = strtolower($usuario);
        $primer_apellido = strtolower($primer_apellido);
        $segundo_apellido = strtolower($segundo_apellido);
        $email = strtolower($email);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $mensaje = "Correo electrónico no válido.";
            $tipo = "alert-danger";
            header("Location: registro.php?mensaje=" . urlencode($mensaje) . "&tipo=" . urlencode($tipo));
            exit();
        } elseif (strlen($contrasenia) < 8) {
            $mensaje = "La contraseña debe tener al menos 8 caracteres.";
            $tipo = "alert-danger";
            header("Location: registro.php?mensaje=" . urlencode($mensaje) . "&tipo=" . urlencode($tipo));
            exit();
        } elseif (!preg_match('/^\d{9}$/', $telefono)) {
            $mensaje = "El número de teléfono no es válido.";
            $tipo = "alert-danger";
            header("Location: registro.php?mensaje=" . urlencode($mensaje) . "&tipo=" . urlencode($tipo));
            exit();
        } else {
            $query = "SELECT id_usuarios FROM usuarios 
                      WHERE email = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $resultado = $stmt->get_result();

            if ($resultado->num_rows > 0) {
                $mensaje = "El correo electrónico ya está registrado.";
                $tipo = "alert-danger";
                header("Location: registro.php?mensaje=" . urlencode($mensaje) . "&tipo=" . urlencode($tipo));
                exit();
            } else {
                $query = "SELECT id_usuarios FROM usuarios 
                          WHERE telefono = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param('s', $telefono);
                $stmt->execute();
                $resultado = $stmt->get_result();

                if ($resultado->num_rows > 0) {
                    $mensaje = "El número de teléfono ya está registrado.";
                    $tipo = "alert-danger";
                    header("Location: registro.php?mensaje=" . urlencode($mensaje) . "&tipo=" . urlencode($tipo));
                    exit();
                } else {
                    // Contraseña en hash
                    $contrasenia_hash = password_hash($contrasenia, PASSWORD_DEFAULT);
                    $query = "INSERT INTO usuarios (nombre, primer_apellido, segundo_apellido, email, telefono, contrasena) VALUES (?,?,?,?,?,?)";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param('ssssss', $usuario, $primer_apellido, $segundo_apellido, $email, $telefono, $contrasenia_hash);
                    if ($stmt->execute()) {
                        $mensaje = "Usuario creado correctamente.";
                        $tipo = "alert-success";
                        header("Location: registro.php?mensaje=" . urlencode($mensaje) . "&tipo=" . urlencode($tipo));
                        exit();
                    } else {
                        $mensaje = "El usuario no ha sido creado.";
                        $tipo = "alert-danger";
                        header("Location: registro.php?mensaje=" . urlencode($mensaje) . "&tipo=" . urlencode($tipo));
                        exit();
                    }
                }
            }
            $stmt->close();
        }
    }
?>
