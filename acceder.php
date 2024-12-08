<?php
    // Verifica si hay una sesión activa
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    include_once("header/navegadorCinco.php");

    // Capturar mensaje y tipo 
    $mensaje = isset($_GET['mensaje']) ? htmlspecialchars($_GET['mensaje']) : '';
    $tipo = isset($_GET['tipo']) ? htmlspecialchars($_GET['tipo']) : '';
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Clínica Vitalmente | Acceder</title>
        <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
        <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
        <link rel="stylesheet" href="css/estilosAcceder.css">
    </head>
    <body>
        <!-- Notificaciones -->
        <?php if (!empty($mensaje) && !empty($tipo)): ?>
            <div id="notificacion" class="alert <?= $tipo; ?> text-center" role="alert">
                <?= $mensaje; ?>
            </div>
        <?php endif; ?>

        <main class="main-content">
            <section class="py-5">
                <div class="container">
                    <div class="row align-items-center">
                        <!-- Imagen -->
                        <div class="col-lg-6 d-none d-lg-block">
                            <img src="img/principal.png" alt="Acceder" class="img-fluid rounded">
                        </div>
                        <!-- Formulario -->
                        <div class="col-lg-6">
                            <div class="card shadow p-4 border-0">
                                <h1 class="text-center mb-4">Iniciar Sesión</h1>
                                <form action="login.php" method="post">
                                    <!-- Usuario -->
                                    <div class="mb-3">
                                        <label for="usuario" class="form-label">Email</label>
                                        <input type="email" name="usuario" id="usuario" class="form-control" placeholder="ejemplo@gmail.com" required>
                                    </div>
                                    <!--  Contraseña -->
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Contraseña</label>
                                        <input type="password" name="password" id="password" class="form-control" placeholder="Contraseña" required>
                                    </div>
                                    <!-- Botón -->
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary btn-block">Iniciar Sesión</button>
                                    </div>
                                </form>
                                <!-- Enlaces -->
                                <div class="text-center mt-3">
                                    <a href="cambiar_contrasenia.php" class="text-decoration-none">¿Has olvidado tu contraseña?</a><br>
                                    <a href="registro.php" class="text-decoration-none">Registrarse</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
        <!-- Script para Ocultar Notificaciones -->
        <script>
            window.onload = function() {
                const notificacion = document.getElementById('notificacion');
                if (notificacion) {
                    setTimeout(() => {
                        notificacion.style.opacity = '0';
                    }, 2000);
                    setTimeout(() => {
                        notificacion.remove();
                    }, 4500);
                }
            };
        </script>
    </body>
</html>
<?php include_once("footer/footer.php"); ?>
