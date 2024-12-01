<?php
    if(session_status() === PHP_SESSION_NONE){
        session_start();
    }

    include_once("config.php");

    // Capturar mensaje y tipo 
    $mensaje = isset($_GET['mensaje']) ? htmlspecialchars($_GET['mensaje']) : '';
    $tipo = isset($_GET['tipo']) ? htmlspecialchars($_GET['tipo']) : '';

    $query = "SELECT usuarios.nombre, usuarios.primer_apellido, usuarios.segundo_apellido, usuarios.email, usuarios.telefono, profesionales.foto, profesionales.especialidad, profesionales.descripcion  FROM usuarios
               INNER JOIN profesionales
               ON usuarios.id_usuarios = profesionales.id_usuario
               WHERE estado != 'baja'";
    $stmt = $conn->query($query);
?>
<!DOCTYPE html>
<html lan="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Clínica Vitalmente | Servicios</title>
        <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
        <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
        <link rel="stylesheet" href="css/estilosServicios.css">
    </head>
    <body>
        <!-- Notificaciones -->
        <?php if (!empty($mensaje) && !empty($tipo)): ?>
            <div id="notificacion" class="alert <?= $tipo; ?> text-center" role="alert">
                <?= $mensaje; ?>
            </div>
        <?php endif; ?>

        <div class="container-fluid mt-5">            
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                <?php
                    if($stmt && $stmt->num_rows > 0){
                        while($profesional = $stmt->fetch_assoc()){
                            ?>
                                <div class="col">
                                    <div class="card h-100">
                                        <h1 class="card-title"><?= $profesional['nombre'] . ' ' . $profesional['primer_apellido'] . ' ' . $profesional['segundo_apellido']; ?></h1>
                                        <img src='<?= $profesional['foto']; ?>' class="card-img-top" alt='<?= $profesional['nombre']; ?>'>
                                        <div class="card-body">
                                            <p class="card-text"><span><?= $profesional['especialidad']; ?></span></p>
                                            <p class="card-text"><?= $profesional['descripcion']; ?></p>
                                        </div>
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item text-info" ><span class="text-dark">Teléfono:</span> <?= $profesional['telefono']; ?></li>
                                            <li class="list-group-item text-info"><span class="text-dark">Email:</span> <?= $profesional['email']; ?></li>
                                        </ul>
                                        <div class="card-footer">
                                            <a href="eliminarProfesional.php?id_profesionales=' . $profesional['id_profesionales'] . '" class="btn btn-danger w-100">Eliminar Profesional</a>
                                        </div>
                                    </div>
                                </div>
                            <?php
                        }
                    }else {
                        echo "No se encuentran profesionales";
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
