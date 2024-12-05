<?php
    if(session_status() === PHP_SESSION_NONE){
        session_start();
    }

    include_once("config.php");

    // Verifica si el usuario está autenticado
    if (!isset($_SESSION['tipo_usuario'])) {
        header("Location: acceder.php");
        exit();
    }

    // Solo permite acceso a administradores
    if ($_SESSION['tipo_usuario'] !== 'administrador') {
        header("Location: acceder.php");
        exit();
    }

    // Capturar mensaje y tipo 
    $mensaje = isset($_GET['mensaje']) ? htmlspecialchars($_GET['mensaje']) : '';
    $tipo = isset($_GET['tipo']) ? htmlspecialchars($_GET['tipo']) : '';

    $query = "SELECT * FROM servicios
              WHERE estado != 'eliminado'";
    $stmt = $conn->query($query);
?>
<!DOCTYPE html>
<html lan="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Clínica Vitalmente | Eliminar Servicio</title>
        <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
        <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
        <link rel="stylesheet" href="css/estilosServicios.css">
        <script>
            function confirmarEliminacion (event) {
                if(!confirm('¿Seguro que quiere eliminar el servicio?')) {
                    event.preventDefault();
                }
            }
        </script>
    </head>
    <body>
        <!-- Notificaciones -->
        <?php if (!empty($mensaje) && !empty($tipo)): ?>
            <div id="notificacion" class="alert <?= $tipo; ?> text-center" role="alert">
                <?= $mensaje; ?>
            </div>
        <?php endif; ?>

        <!-- Servicios disponibles en la clínica  -->
        <div class="container-fluid mt-5">
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                <?php
                    if($stmt->num_rows > 0){
                        while($servicios = $stmt->fetch_assoc()){
                            ?>
                            <div class="col mt-5">
                                <div class="card h-100">
                                    <h1 class="card-title text-center"><?= $servicios['nombre_servicio']; ?></h1>
                                    <img src='<?= $imagen = "img/servicios/" . htmlspecialchars($servicios['imagen']); ?>' class="card-img-top" alt='<?= $servicios['nombre_servicio']; ?>'>
                                    <div class="card-body">
                                        <p class="card-text"><?= $servicios['descripcion']; ?></p>
                                    </div>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item"><?= $servicios['asistencia']; ?></li>
                                        <li class="list-group-item precio text-danger"><?= $servicios['precio']; ?> €</li>
                                    </ul>
                                    <div class="card-footer boton">
                                        <form action="eliminarServicio.php" method="POST">
                                            <input type="hidden" name="id_servicios" value="<?= $servicios['id_servicios']; ?>">
                                            <button type="submit" class="btn btn-danger w-100" onclick="confirmarEliminacion(event)">Eliminar Servicio</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    }else {
                        echo "Servicios no disponibles";
                    }
                ?>
            </div>
            <a href="admin.php"><button type="submit" class="btn btn-secondary mt-5">Regresar</button></a>
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
