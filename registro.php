<?php
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
        <title>Clínica Vitalmente | Registro</title>
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

        <section class="py-5">
            <div class="container">
                <div class="row align-items-center">
                    <!-- Imagen -->
                    <div class="col-lg-6 d-none d-lg-block">
                        <img src="img/secundario.png" alt="Registro" class="img-fluid rounded">
                    </div>
                    <!-- Formulario -->
                    <div class="col-lg-6">
                        <div class="card shadow p-4 border-0">
                            <h1 class="text-center mb-4">Registrarse</h1>
                            <form action="insertarUsuario.php" method="post">
                                <!-- Nombre -->
                                <div class="mb-3">
                                    <label for="nombre" class="form-label">Nombre</label>
                                    <input type="text" name="nombre" id="nombre" class="form-control" required>
                                </div>
                                <!-- Primer Apellido -->
                                <div class="mb-3">
                                    <label for="primer_apellido" class="form-label">Primer Apellido</label>
                                    <input type="text" name="primer_apellido" id="primer_apellido" class="form-control" required>
                                </div>
                                <!-- Segundo Apellido -->
                                <div class="mb-3">
                                    <label for="segundo_apellido" class="form-label">Segundo Apellido</label>
                                    <input type="text" name="segundo_apellido" id="segundo_apellido" class="form-control">
                                </div>
                                <!-- Correo -->
                                <div class="mb-3">
                                    <label for="mail" class="form-label">Correo Electrónico</label>
                                    <input type="email" name="mail" id="mail" class="form-control" placeholder="ejemplo@gmail.com" required>
                                </div>
                                <!-- Contraseña -->
                                <div class="mb-3">
                                    <label for="password" class="form-label">Contraseña</label>
                                    <input type="password" name="password" id="password" class="form-control" minlength="8" required>
                                </div>
                                <!-- Teléfono -->
                                <div class="mb-3">
                                    <label for="telefono" class="form-label">Teléfono</label>
                                    <input type="tel" name="telefono" id="telefono" class="form-control" minlength="9" maxlength="9" required>
                                </div>
                                <!-- Botón -->
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary btn-block">Registrarse</button>
                                </div>
                            </form>
                            <!-- Enlace -->
                            <div class="text-center mt-3">
                                <a href="acceder.php" class="text-decoration-none">Inicia Sesión</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

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
