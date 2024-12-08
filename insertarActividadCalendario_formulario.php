<?php
    // Verifica si hay una sesión activa
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Incluye la configuración de la base de datos
    include_once('config.php');

    // Verifica si el usuario está autenticado
    if (isset($_SESSION['id_usuarios']) && $_SESSION['tipo_usuario'] === 'administrador') {
        $id_admin = $_SESSION['id_usuarios'];
    }else {
        header("Location: acceder.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Clínica VitalMente | Agregar Actividad</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="css/estilosActividad.css"> 
    </head>
    <body>
        <!-- Mostrar mensaje -->
        <?php if(isset($_SESSION['mensaje'])): ?>
            <div class="alert <?= $_SESSION['tipo']; ?> text-center" role="alert">
                <?= $_SESSION['mensaje']; ?>
            </div>
            <?php
                unset($_SESSION['mensaje'], $_SESSION['tipo']);
            ?>
        <?php endif; ?>

        <div class="container mt-5">
            <div class="form-container shadow-lg p-4 rounded">
                <h1 class="text-center mb-4">Añadir Nueva Actividad</h1>
                <form action="insertarActividades_calendario.php" method="POST">
                    <div class="mb-3">
                        <label for="titulo" class="form-label">Título de la actividad:</label>
                        <input type="text" name="titulo" id="titulo" class="form-control input-custom" placeholder="Ingrese el título" required>
                    </div>

                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción:</label>
                        <textarea name="descripcion" id="descripcion" rows="5" class="form-control input-custom" placeholder="Actividad a realizar" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="fecha" class="form-label">Fecha:</label>
                        <input type="date" name="fecha" id="fecha" class="form-control input-custom" required>
                    </div>

                    <div class="row">
                        <div class="col-12 col-md-6 mb-3">
                            <label for="hora_inicio" class="form-label">Hora inicio</label>
                            <input type="time" name="hora_inicio" id="hora_inicio" class="form-control input-custom" required>
                        </div>

                        <div class="col-12 col-md-6 mb-3">
                            <label for="hora_fin" class="form-label">Hora fin</label>
                            <input type="time" name="hora_fin" id="hora_fin" class="form-control input-custom" required>
                        </div>
                    </div>

                    <!-- Campo oculto para enviar id_admin -->
                    <input type="hidden" name="id_admin" value="<?php echo $id_admin; ?>">
                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-primary">Agregar Actividad</button>
                        <a href="admin.php" class="btn btn-secondary">Regresar</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            // Script para que desaparezcan las notificaciones.
            window.onload = function(){
                    const notificacion = document.getElementById('notificacion');
                    if(notificacion){
                        setTimeout(() => {
                            notificacion.style.opacity = '0';
                        }, 4000);
                        setTimeout(() => {
                            notificacion.remove();
                        }, 6000)
                    }
                };
        </script>
    </body>
</html>
