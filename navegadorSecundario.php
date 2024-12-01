<?php
    if(session_status() === PHP_SESSION_NONE){
        session_start();
    }
    include_once("config.php");
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title></title>
        <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
        <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
        <link rel="stylesheet" href="css/estilosNavegador.css">
    </head>
    <body>
        <header class="bg-light shadow">
            <div class="container-fluid d-flex justify-content-between align-items-center p-3">
                <div class="logo">
                    <img src="img/logo.png" alt="Logo" class="img-fluid" style="max-height: 80px;">
                </div>
                <nav class="navbar navbar-expand-lg navbar-light">
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav2" aria-controls="navbarNav2" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav2">
                        <ul class="navbar-nav ms-auto">
                            <?php
                                if (isset($_SESSION['id_usuarios'])) {
                                    $query = "SELECT tipo_usuario FROM usuarios WHERE id_usuarios = ?";
                                    $stmt = $conn->prepare($query);
                                    $stmt->bind_param('i', $_SESSION['id_usuarios']);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    $usuario = $result->fetch_assoc();
                                    $tipo_usuario = $usuario['tipo_usuario'];

                                    if ($tipo_usuario === 'administrador') {
                                        echo '<a href="admin.php" class="btn btn-outline-secondary">Inicio</a>';
                                        echo '<a href="cerrar_sesion.php" class="btn btn-success">Cerrar Sesión</a>';
                                    } elseif ($tipo_usuario === 'normal') {
                                        echo '<a href="pacientes.php" class="btn btn-outline-secondary">Perfil</a>';
                                        echo '<a href="actividades.php" class="btn btn-outline-secondary">Actividades</a>';
                                        echo '<a href="cerrar_sesion.php" class="btn btn-success">Cerrar Sesión</a>';
                                    }
                                    $stmt->close();
                                } else {
                                    echo '<a href="index.php" class="btn btn-outline-secondary">Inicio</a>';
                                    echo '<a href="contacto.php" class="btn btn-outline-secondary">Contacto</a>';
                                    echo '<a href="actividades.php" class="btn btn-outline-secondary">Actividades</a>';
                                    echo '<a href="acceder.php" class="btn btn-success">Acceder</a>';
                                }
                            ?>
                        </ul>
                    </div>
                </nav>
            </div>
        </header>
    </body>
</html>
