<?php
    include_once("config.php");
    include_once("header/navegadorInicio.php");

    $query = "SELECT * FROM servicios 
               WHERE estado != 'eliminado' AND nombre_servicio 
               IN ('Ansiedad','Terapia familiar','Adicciones')";
    $stmt = $conn->query($query);
?>
<!DOCTYPE html>
<html lan="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Inicio</title>
        <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
        <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
        <link rel="stylesheet" href="css/estilos.css">
    </head>
    <body>
        <section class="principal">
            <img src="img/portada2.jpg" alt="portada">
            <h2>CLÍNICA VITALMENTE</h2>
        </section>
        <section class="info">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-4">
                        <div class="carousel slide" id="primer-carousel" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                <div class="carousel-item active">
                                    <img src="img/carrusel/imagen1.jpg" alt="" class="carr">
                                </div>
                                <div class="carousel-item">
                                    <img src="img/carrusel/imagen2.jpg" alt="" class="carr">
                                </div>
                                <div class="carousel-item">
                                    <img src="img/carrusel/imagen3.jpg" alt="" class="carr">
                                </div>
                                <div class="carousel-item ">
                                    <img src="img/carrusel/imagen4.jpg" alt="" class="carr">
                                </div>
                                <div class="carousel-item">
                                    <img src="img/carrusel/imagen5.jpg" alt="" class="carr">
                                </div>
                                <div class="carousel-item">
                                    <img src="img/carrusel/imagen6.jpg" alt="" class="carr">
                                </div>
                                <div class="carousel-item ">
                                    <img src="img/carrusel/imagen7.jpg" alt="" class="carr">
                                </div>
                                <div class="carousel-item">
                                    <img src="img/carrusel/imagen8.jpg" alt="" class="carr">
                                </div>
                                <div class="carousel-item">
                                    <img src="img/carrusel/imagen9.jpg" alt="" class="carr">
                                </div>
                                <div class="carousel-item">
                                    <img src="img/carrusel/imagen10.jpg" alt="" class="carr">
                                </div>
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#primer-carousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Anterior</span>
                            </button>
                            <button  class="carousel-control-next" type="button" data-bs-target="#primer-carousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Siguiente</span>
                            </button>
                        </div>
                    </div>
                    <div class="col-8 mt-5" class="texto">
                        <h1>Conócenos</h1>
                        <p>En Clínica VitalMente, nos dedicamos a cuidar de la salud mental y emocional de nuestros pacientes con un enfoque integral y personalizado. Nuestro objetivo es crear un espacio seguro y de confianza, donde cada persona pueda encontrar el apoyo y las herramientas necesarias para mejorar su bienestar psicológico.
                           <br><br>
                           Contamos con un equipo de profesionales altamente calificados en diversas áreas de la salud mental, incluyendo psicología clínica, psiquiatría y terapia ocupacional, entre otros. Nos especializamos en el tratamiento de trastornos como la depresión, la ansiedad, el estrés postraumático, entre otros, y trabajamos tanto con adultos como con adolescentes y niños.
                           <br><br>
                           Creemos firmemente en el poder de la prevención y en la importancia de atender la salud mental de manera proactiva. Ofrecemos programas terapéuticos adaptados a las necesidades de cada persona, con un enfoque centrado en el bienestar holístico, combinando métodos tradicionales y terapias innovadoras.
                           Nuestro compromiso es brindarte un acompañamiento cercano en cada paso de tu proceso de sanación, ayudándote a recuperar el equilibrio emocional y mental necesario para una vida plena y saludable. Aquí, el cuidado va más allá del tratamiento: creamos un ambiente acogedor en el que te sentirás comprendido y apoyado.</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <h2 class="text-center">Servicios</h2>
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
                                                <div>
                                                    <?php
                                                        if(isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] == 'administrador'){
                                                            echo '<a href="eliminarServicio.php?id_servicios=' . $servicios['id_servicios'] . '" class="btn btn-danger">Eliminar Servicios</a>';
                                                        }else {
                                                            echo '<a href="servicios.php?id_servicios=' . $servicios['id_servicios'] . '" class="btn btn-secondary">Saber más</a>';
                                                        }
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
                    </div>
                </div>
            </div>
        </section>
        <?php include_once("footer/footer.php"); ?>
    </body>
</html>