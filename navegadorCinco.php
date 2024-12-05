<?php
    if($_SERVER['HTTP_HOST'] == 'localhost') {
        define('BASE_URL', 'http://localhost/ClinicaVitalMente/');
    }else {
        define('BASE_URL', 'https://vitalmenteclinica.es/');
    }
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
                    <a href="index.php"><img src="img/logo.png" alt="Logo" class="img-fluid" style="max-height: 80px;"></a>
                </div>
                <nav class="navbar navbar-expand-lg navbar-light">
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav5" aria-controls="navbarNav5" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav5">
                        <ul class="navbar-nav ms-auto">
                            <a href="index.php" class="btn btn-outline-secondary">Inicio</a>
                            <a href="servicios.php" class="btn btn-outline-secondary">Servicios</a>
                            <a href="contacto.php" class="btn btn-outline-secondary">Contacto</a>
                            <a href="actividades.php" class="btn btn-outline-secondary">Actividades</a>
                        </ul>
                    </div>
                </nav>
            </div>
        </header>
    </body>
</html>
