<?php

    include_once("header/NavegadorServicios.php");
?>
<!DOCTYPE html>
<html lan="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Contacto</title>
        <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
        <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
        <link rel="stylesheet" href="css/estilosContacto.css">
    </head>
    <body>
        <section class="contacto">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6">
                        <div class="contacto-info">
                            <a href=""><img src="img/iconoFacebook.png"> Clínica Vitalmente</a>
                            <a href=""><img src="img/iconoInstagram.png"> @vitalmente</a>
                            <a href=""><img src="img/iconoYoutube.png"> Clínica Vitalmente</a>
                            <a href=""><img src="img/iconoContacto.png"> 673925379</a>
                            <a href=""><img src="img/iconoEmail.png"> vitalmente@gmail.com</a>
                        </div>
                    </div>
                    <div class="col-md-6 formulario">
                        <h1>Contacta con nosotro a través de: </h1>
                        <div class="info">
                                <p>¿Tienes alguna pregunta o necesitas ayuda? Escríbenos si tienes dudas sobre nuestros servicios, deseas agendar una cita o simplemente necesitas más información. Nuestro equipo estará encantado de ayudarte y responder a tus consultas. Completa el formulario y nos pondremos en contacto contigo lo antes posible. ¡Estamos aquí para escucharte!</p>
                        </div>
                        <form action="mailto:infoVitalmente@gmail.com" method="POST">
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre </label>
                                <input type="text" name="nombre" id="nombre" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Correo Electrónico </label>
                                <input type="email" name="email" id="email" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="mensaje" class="form-label">Mensaje </label>
                                <textarea name="mensaje" id="mensaje" row="10" class="form-control" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-secondary">Enviar</button>
                        </form>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-md-12">
                        
                            <!-- <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d2618.186606843117!2d-3.3754600161631014!3d40.4751717734887!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1ses!2ses!4v1728238789274!5m2!1ses!2ses" width="100%" height="250" style="border:0;" allowfullscreen="" loading="lazy"></iframe> -->
                                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3037.4424839131198!2d-3.7070347241223556!3d40.42119995536747!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd42287d1255bd35%3A0x3ea33bb3cfbdbcc1!2sC.%20del%20Desenga%C3%B1o%2C%2026%2C%20Centro%2C%2028004%20Madrid!5e0!3m2!1ses!2ses!4v1730048255253!5m2!1ses!2ses" width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                        
                    </div>
                </div>
            </div>
        </section>
        <?php include_once("footer/footer.php"); ?>
    </body>
</html>