<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once("config.php");
include_once("header/navegadorCinco.php");

// Variables para mostrar mensajes de éxito o error 
$mensaje = '';
$tipo = '';

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $usuario = $_POST['usuario'];
    $contrasenia = $_POST['password'];

    // Limpiar los campos
    $usuario = trim(htmlspecialchars($usuario));
    $contrasenia = htmlspecialchars($contrasenia);

    // Convertir usuario a minúscula
    $usuario = strtolower($usuario);

    // Sentencia SQL
    $query = "SELECT id_usuarios, nombre, email, contrasena, tipo_usuario FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        echo "Error en la consulta: " . $conn->error;
        exit();
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
            if ($login['tipo_usuario'] === 'administrador') {
                header("Location: admin.php");
                exit();
            } elseif ($login['tipo_usuario'] === 'profesional') {
                header("Location: profesionales.php");
                exit();
            } else {
                header("Location: pacientes.php");
                exit();
            }
        } else {
            $mensaje = "Contraseña incorrecta";
            $tipo = "error";
        }
    } else {
        $mensaje = "Usuario no encontrado";
        $tipo = "error";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Clínica Vitalmente | Acceder</title>
        <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
        <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
        <link rel="stylesheet" href="css/estilos.css">
    </head>
    <body>
        <!-- Para mostrar las notificaciones-->
        <?php if ($mensaje): ?>
            <div id="notificacion" class="notificacion <?= $tipo; ?>">
                <?= $mensaje; ?>
            </div>
        <?php endif; ?>
        <section>
            <div class="container">
                <div class="derecha">
                    <img src="img/principal.png" alt="acceder" class="imagen">
                </div>
                <div class="izquierda">
                    <h1>Iniciar Sesión</h1>
                    <form action=" " method="post">
                        <label for="usuario">Email</label>
                        <input type="email" name="usuario" id="usuario" placeholder="ejemplo@gmail.com" required>

                        <label for="password">Contraseña</label>
                        <input type="password" name="password" id="password" placeholder="Contraseña" required>

                        <button type="submit" class="submit-btn">Iniciar Sesión</button>
                        <a href="cambiar_contrasenia.php" class="registrar">¿Has olvidado tu contraseña?</a>
                        <a href="registro.php" class="registrar">Registrarse</a>
                    </form>
                </div>
            </div>
        </section>
        <!-- Script para que desaparezcan las notificaciones-->
        <script>
            window.onload = function() {
                const notificacion = document.getElementById('notificacion');
                if (notificacion) {
                    setTimeout(() => {
                        notificacion.style.opacity = '0';
                    }, 600);
                    setTimeout(() => {
                        notificacion.remove();
                    }, 4500);
                }
            };
        </script>
    </body>
</html>
<?php include_once("footer/footer.php"); ?>
