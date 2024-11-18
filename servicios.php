<?php
    if(session_status() === PHP_SESSION_NONE){
        session_start();
    }
    include_once("config.php");
    include_once("header/navegadorSecundario.php");

    $query = "SELECT * FROM servicios
              WHERE estado != 'eliminado'";
    $stmt = $conn->query($query);
    $query2 = "SELECT * FROM profesionales
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
        <link rel="stylesheet" href="css/estiloServicios.css">
    </head>
    <body>
        <div class="container-fluid">
                <h2 class="text-center">Servicios</h2>
                <div class="info">
                    <p>En nuestra clínica, ofrecemos una variedad de servicios de salud mental adaptados a las necesidades de cada paciente. Nos enfocamos en brindar apoyo profesional y acompañamiento en el proceso de mejora emocional y mental, con un equipo especializado y comprometido en proporcionar una atención integral. Cada servicio está diseñado para abordar de manera eficaz los desafíos que afectan la vida de nuestros pacientes, promoviendo su bienestar y desarrollo personal.</p>
                </div>
                    <div class=" card-deck">
                        <?php
                            if($stmt->num_rows > 0){
                                while($servicios = $stmt->fetch_assoc()){
                                    ?>
                                    <div class="card">
                                        <h1 class="card-title"><?= $servicios['nombre_servicio']; ?></h1>
                                        <img src='<?= $servicios['imagen']; ?>' class="card-img-top" alt='<?= $servicios['nombre_servicio']; ?>'>
                                            <div class="card-body">
                                                <p class="card-text"><?= $servicios['descripcion']; ?></p>
                                            </div>
                                            <ul class="list-group list-group-flush">
                                                <li class="list-group-item"><?= $servicios['asistencia']; ?></li>
                                                <li class="list-group-item precio"><?= $servicios['precio']; ?> €</li>
                                            </ul>
                                            <div>
                                                <?php
                                                    if(isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] == 'administrador'){
                                                        echo '<a href="eliminarServicio.php?id_servicios=' . $servicios['id_servicios'] . '" class="btn btn-danger">Eliminar Servicios</a>';
                                                    }elseif(isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] == 'normal') {
                                                        echo '<a href="reservarServicio.php?id_servicios=' . $servicios['id_servicios'] . '" class="btn btn-secondary">Reservar</a>';
                                                    }//else {
                                                    //     echo '<a href="acceder.php" class="btn btn-secondary">Reservar</a>';
                                                    // }
                                                ?>
                                            </div>
                                    </div>
                                    <?php
                                }
                            }else {
                                echo "Servicios no disponibles";
                            }
                        ?>
                    </div>
                        <h2 class="text-center">Profesionales</h2>
                        <div class="info">
                            <p>Nuestro equipo está compuesto por un grupo de profesionales altamente cualificados y dedicados a proporcionar la mejor atención posible. Cada miembro de nuestro equipo posee una amplia experiencia y se especializa en diversas áreas de la salud mental, lo que nos permite ofrecer un enfoque multidisciplinario para el tratamiento. Nos esforzamos por crear un ambiente seguro y de apoyo, donde nuestros pacientes puedan encontrar el acompañamiento y las herramientas necesarias para mejorar su calidad de vida.</p>
                        </div>
                        <div class=" card-deck">
                            <?php
                                if($stmt2->num_rows > 0){
                                    while($profesional = $stmt2->fetch_assoc()){
                                        ?>
                                        <div class="card">
                                        <h1 class="card-title"><?= $profesional['nombre']; ?></h1>
                                            <img src='<?= $profesional['foto']; ?>' class="card-img-top" alt='<?= $profesional['nombre']; ?>'>
                                                <div class="card-body">
                                                    <p class="card-text"><span><?= $profesional['especialidad']; ?></span></p>
                                                    <p class="card-text"><?= $profesional['descripcion']; ?></p>
                                                </div>
                                                <ul class="list-group list-group-flush">
                                                    <li class="list-group-item"><span>Teléfono:</span> <?= $profesional['telefono']; ?></li>
                                                    <li class="list-group-item"><span>Email:</span> <?= $profesional['email']; ?></li>
                                                </ul>
                                                <div>
                                                    <?php
                                                        if(isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] == 'administrador'){
                                                            echo '<a href="eliminarProfesional.php?id_profesionales=' . $profesional['id_profesionales'] . '" class="btn btn-danger">Eliminar Profesional</a>';
                                                        }
                                                    ?>
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
<?php include_once("footer/footer.php");