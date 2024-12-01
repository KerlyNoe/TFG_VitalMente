<?php
    if(session_status() === PHP_SESSION_NONE){
        session_start();
    }

    include_once("config.php");

    include_once("header/navegadorSecundario.php");

    $query = "SELECT * FROM servicios
              WHERE estado != 'eliminado'";
    $stmt = $conn->query($query);
    $query2 = "SELECT usuarios.nombre, usuarios.primer_apellido, usuarios.segundo_apellido, usuarios.email, usuarios.telefono, profesionales.foto, profesionales.especialidad, profesionales.descripcion  FROM usuarios
               INNER JOIN profesionales
               ON usuarios.id_usuarios = profesionales.id_usuario
               WHERE estado != 'baja'";
    $stmt2 = $conn->query($query2);
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

        <!-- Servicios disponibles en la clínica  -->
        <div class="container-fluid">
            <h2 class="text-center">Servicios</h2>
            <div class="info mb-4">
                <p>En nuestra clínica, ofrecemos una variedad de servicios de salud mental adaptados a las necesidades de cada paciente. Nos enfocamos en brindar apoyo profesional y acompañamiento en el proceso de mejora emocional y mental, con un equipo especializado y comprometido en proporcionar una atención integral. Cada servicio está diseñado para abordar de manera eficaz los desafíos que afectan la vida de nuestros pacientes, promoviendo su bienestar y desarrollo personal.</p>
            </div>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                <?php
                    if($stmt->num_rows > 0){
                        while($servicios = $stmt->fetch_assoc()){
                            ?>
                            <div class="col">
                                <div class="card h-100">
                                    <h1 class="card-title text-center"><?= $servicios['nombre_servicio']; ?></h1>
                                    <img src='<?= $servicios['imagen']; ?>' class="card-img-top" alt='<?= $servicios['nombre_servicio']; ?>'>
                                    <div class="card-body">
                                        <p class="card-text"><?= $servicios['descripcion']; ?></p>
                                    </div>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item"><?= $servicios['asistencia']; ?></li>
                                        <li class="list-group-item precio text-danger"><?= $servicios['precio']; ?> €</li>
                                    </ul>
                                    <div class="card-footer boton">
                                        <a href="acceder.php" class="btn btn-success w-100">Reservar</a>
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

            <!-- Profesionales disponibles en la clínica -->
            <h2 class="text-center mt-5 mb-4">Profesionales</h2>
            <div class="info mb-4">
                <p>Nuestro equipo está compuesto por un grupo de profesionales altamente cualificados y dedicados a proporcionar la mejor atención posible. Cada miembro de nuestro equipo posee una amplia experiencia y se especializa en diversas áreas de la salud mental, lo que nos permite ofrecer un enfoque multidisciplinario para el tratamiento. Nos esforzamos por crear un ambiente seguro y de apoyo, donde nuestros pacientes puedan encontrar el acompañamiento y las herramientas necesarias para mejorar su calidad de vida.</p>
            </div>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                <?php
                    if($stmt2 && $stmt2->num_rows > 0){
                        while($profesional = $stmt2->fetch_assoc()){
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
                                    </div>
                                </div>
                            <?php
                        }
                    }else {
                        echo "No se encuentran profesionales";
                    }
                ?>
            </div>
        </div>
    </body>
</html>
<?php include_once("footer/footer.php"); ?>
