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

    // Capturar mensaje y tipo 
    $mensaje = isset($_GET['mensaje']) ? htmlspecialchars($_GET['mensaje']) : '';
    $tipo = isset($_GET['tipo']) ? htmlspecialchars($_GET['tipo']) : '';
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Clínica Vitalmente | Insertar Servicios</title>
        <link rel="stylesheet" href="css/insertarServicio.css">
        <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
        <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
    </head>
    <body>
        <!-- Notificaciones -->
        <?php if (!empty($mensaje) && !empty($tipo)): ?>
            <div id="notificacion" class="alert <?= $tipo; ?> text-center" role="alert">
                <?= $mensaje; ?>
            </div>
        <?php endif; ?>

        <div class="container mt-4">
            <div class="row justify-content-center">
                <div class="col-12 col-md-8 col-lg-6 form-container">
                    <h1 class="text-center mb-4">Añadir Nuevo Profesional</h1>

                    <form action="insertarProfesional.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                            <label for="id" class="form-label">ID de usuario:</label>
                            <input type="number" name="id" id="id" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="imagen" class="form-label">Agregar imagen:</label>
                            <input type="text" name="imagen" id="imagen" class="form-control" accept="image/*" required>
                        </div>

                        <div class="mb-3">
                            <label for="especialidad" class="form-label">Especialidad:</label>
                            <textarea name="especialidad" id="especialidad" class="form-control" rows="5" required>Agrega un texto</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripcion:</label>
                            <input type="text" name="descripcion" id="descripcion" class="form-control">
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-success">Añadir Profesional</button>
                        </div>
                    </form>
                    <a href="admin.php"><button type="submit" class="btn btn-secondary">Regresar</button></a>
                </div>
            </div>
        </div>
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
