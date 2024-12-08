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
        <footer class="bg-light py-4" id="footer">
            <div class="container">
                <div class="row text-center text-md-start">
                    <div class="col-12 col-md-6 d-flex justify-content-center justify-content-md-start mb-3 mb-md-0 gap-3">
                        <a href="www.facebook.com"><img src="img/iconoFacebook.png" class="img-fluid" alt="Facebook" style="max-height: 30px;"></a>
                        <a href="www.instagram.com"><img src="img/iconoInstagram.png" class="img-fluid" alt="Instagram" style="max-height: 30px;"></a>
                        <a href="www.youtube.com"><img src="img/iconoYoutube.png" class="img-fluid" alt="YouTube" style="max-height: 30px;"></a>
                        <a href="www.googlemaps.com"><img src="img/iconoUbicacion.png" class="img-fluid" alt="Ubicación" style="max-height: 30px;"></a>
                        <a href="www.whatsappweb.com"><img src="img/iconoContacto.png" class="img-fluid" alt="Contacto" style="max-height: 30px;"></a>
                    </div>
                    <div class="col-12 col-md-6 d-flex justify-content-center justify-content-md-end align-items-center">
                        <p class="text-muted m-0">&copy; 2024 - VitalMente. Todos los derechos reservados.</p>
                    </div>
                </div>
            </div>
        </footer>
        <!-- Script para volver al incio al dar clic en el footer -->
        <script>
            const footer = document.getElementById('footer');
            footer.addEventListener('click', () => {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        </script>
    </body>
</html>
