<?php
    if(session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    include_once("config.php");
    include_once("header/navegadorTres.php");

    // Capturar mensaje y tipo 
    $mensaje = isset($_GET['mensaje']) ? htmlspecialchars($_GET['mensaje']) : '';
    $tipo = isset($_GET['tipo']) ? htmlspecialchars($_GET['tipo']) : '';
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Clínica Vitalmente | Contacto</title>
        <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
        <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
        <link rel="stylesheet" href="/css/estilosContacto.css">
    </head>
    <body>
        <!-- Notificaciones -->
        <?php if (!empty($mensaje) && !empty($tipo)): ?>
            <div id="notificacion" class="alert <?= $tipo; ?> text-center" role="alert">
                <?= $mensaje; ?>
            </div>
        <?php endif; ?>

        <section class="contacto">
            <div class="container-fluid container-contacto">
                <div class="row">
                    <!-- Columna de información de contacto -->
                    <div class="col-lg-6 col-md-12 contacto-info">
                        <h1 class="text-info ms-5">Contáctanos a través de nuestras redes sociales:</h1>
                        <div class="contacto-icons">
                            <a href="www.facebook.com" class="icono">
                                <img src="img/iconoFacebook.png" alt="Facebook">
                            </a>
                            <a href="www.instagram.com" class="icono">
                                <img src="img/iconoInstagram.png" alt="Instagram">
                            </a>
                            <a href="www.youtube.com" class="icono">
                                <img src="img/iconoYoutube.png" alt="YouTube">                                
                            </a>
                            <a href="tel:673925379" class="icono">
                                <img src="img/iconoContacto.png" alt="Teléfono">
                            </a>
                            <a href="mailto:vitalmente@gmail.com" class="icono">
                                <img src="img/iconoEmail.png" alt="Correo">
                            </a>
                        </div>
                    </div>

                    <!-- Columna de formulario de contacto -->
                    <div class="col-lg-6 col-md-12 formulario">
                        <h1 class="text-info">Contacta con nosotros:</h1>
                        <div class="informacion">
                            <p>¿Tienes alguna pregunta o necesitas ayuda? Escríbenos si tienes dudas sobre nuestros servicios, deseas agendar una cita o simplemente necesitas más información. Nuestro equipo estará encantado de ayudarte y responder a tus consultas. Completa el formulario y nos pondremos en contacto contigo lo antes posible. ¡Estamos aquí para escucharte!</p>
                        </div>
                        <form action="enviarContacto.php" method="POST">
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre</label>
                                <input type="text" name="nombre" id="nombre" class="form-control" autocomplete="name" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Correo Electrónico</label>
                                <input type="email" name="email" id="email" class="form-control" autocomplete="email" required>
                            </div>

                            <div class="mb-3">
                                <label for="mensaje" class="form-label">Mensaje</label>
                                <textarea name="mensaje" id="mensaje" rows="10" class="form-control" autocomplete="on" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-success">Enviar</button>
                        </form>
                    </div>
                </div>

                <!-- Mapa -->
                <div class="row mt-4">
                    <div class="col-12">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3037.4426683096353!2d-3.707034723997983!3d40.421195871438876!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd42287d1255bd35%3A0x3ea33bb3cfbdbcc1!2sC.%20del%20Desenga%C3%B1o%2C%2026%2C%20Centro%2C%2028004%20Madrid!5e0!3m2!1ses!2ses!4v1732661236092!5m2!1ses!2ses" width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                </div>
            </div>
        </section>
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
<?php include_once("footer/footer.php"); ?>
