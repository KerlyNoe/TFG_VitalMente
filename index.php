<?php
    //Verificar si hay una sesión activa.
    if(session_status() === PHP_SESSION_NONE){
        session_start();
    }

    //Configuración de la conexion a la base de datos. 
    include_once("config.php");

    $tipo_usuario = $_SESSION['tipo_usuario'] ?? null;

    //Agregar el navegador.
    if($tipo_usuario === 'normal'){
        include_once("header/navegadorSecundario.php");
    }else {
        include_once("header/navegadorPrimario.php");
    }
    
    //Sentencia que recupera solo los servicios disponibles de la base de datos.
    $query = "SELECT * FROM servicios 
               WHERE estado != 'eliminado' AND nombre_servicio 
               IN ('Ansiedad','Terapia familiar','Adicciones', 'Traumas')";
    $stmt = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Clínica VitalMente | Inicio</title>
        <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
        <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
        <link rel="stylesheet" href="css/estilosInicio.css">
    </head>
    <body>
        <section class="principal">
            <img src="img/portada2.jpg" alt="portada">
            <h2>CLÍNICA VITALMENTE</h2>
        </section>
        <section class="info">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="carousel slide" id="primer-carousel" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                <!-- Imágenes del carrusel -->
                                <?php for ($i = 1; $i <= 10; $i++): ?>
                                    <div class="carousel-item <?= $i == 1 ? 'active' : '' ?>">
                                        <img src="img/carrusel/imagen<?=$i?>.jpg" alt="Imagen <?=$i?>" class="carr">
                                    </div>
                                <?php endfor; ?>
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#primer-carousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Anterior</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#primer-carousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Siguiente</span>
                            </button>
                        </div>
                    </div>

                    <!-- Columna de cocócenos -->
                    <div class="col-lg-8 mt-5">
                        <h1 class="displya-1" >Conócenos</h1>
                        <p>En Clínica VitalMente, nos dedicamos a cuidar de la salud mental y emocional de nuestros pacientes con un enfoque integral y personalizado. Nuestro objetivo es crear un espacio seguro y de confianza, donde cada persona pueda encontrar el apoyo y las herramientas necesarias para mejorar su bienestar psicológico.
                           <br><br>
                           Contamos con un equipo de profesionales altamente calificados en diversas áreas de la salud mental, incluyendo psicología clínica, psiquiatría y terapia ocupacional, entre otros. Nos especializamos en el tratamiento de trastornos como la depresión, la ansiedad, el estrés postraumático, entre otros, y trabajamos tanto con adultos como con adolescentes y niños.
                           <br><br>
                           Creemos firmemente en el poder de la prevención y en la importancia de atender la salud mental de manera proactiva. Ofrecemos programas terapéuticos adaptados a las necesidades de cada persona, con un enfoque centrado en el bienestar holístico, combinando métodos tradicionales y terapias innovadoras.
                           Nuestro compromiso es brindarte un acompañamiento cercano en cada paso de tu proceso de sanación, ayudándote a recuperar el equilibrio emocional y mental necesario para una vida plena y saludable. Aquí, el cuidado va más allá del tratamiento: creamos un ambiente acogedor en el que te sentirás comprendido y apoyado.
                        </p>
                    </div>
                </div>

                <!-- Servicios disponibles -->
                <div class="row">
                    <div class="col-12">
                        <h1 class="text-center display-1">Servicios</h1>
                        <div class="card-deck">
                            <?php
                                if($stmt->num_rows > 0){
                                    while($servicios = $stmt->fetch_assoc()){
                                        ?>
                                        <div class="card">
                                            <h1 class="card-title"><?= $servicios['nombre_servicio']; ?></h1>
                                            <img src='<?= $imagen = "img/servicios/" . htmlspecialchars($servicios['imagen']); ?>' class="card-img-top" alt='<?= $servicios['nombre_servicio']; ?>'>
                                                <div class="card-body">
                                                    <p class="card-text"><?= $servicios['descripcion']; ?></p>
                                                </div>
                                                <div>
                                                    <?php
                                                        if(isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] == 'administrador'){
                                                            echo '<a href="eliminarServicio.php?id_servicios=' . $servicios['id_servicios'] . '" class="btn btn-danger">Eliminar Servicios</a>';
                                                        }else {
                                                            echo '<a href="servicios.php?id_servicios=' . $servicios['id_servicios'] . '" class="btn btn-success">Saber más</a>';
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
    </body>
</html>
<?php include_once("footer/footer.php"); ?>
