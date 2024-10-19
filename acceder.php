<!DOCTYPE html>
<html lan="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Acceder</title>
        <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
        <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
        <link rel="stylesheet" href="css/estilos.css">
    </head>
    <body>
        <header>
            <div class="logo">
                <img src="img/logo.png" alt="logo">
            </div>
            <nav class="nav">
                <a href="inicio.php"> Inicio </a>
                <a href="contacto.php"> Contacto </a>
                <a href="servicios.php"> Servicios </a>
                <a href="actividades.php"> Actividades </a>
            </nav>
        </header>
        <section>
        <div class="container">
            <div class="derecha">
                <img src="img/imagen11.jpg" alt="acceder" class="imagen">
            </div>
            <div class="izquierda">
                <h1>Iniciar Sesión</h1>
                <form action="login.php" method="post">
                    <label for="usuario">Email</label>
                    <input type="email" name="usuario" id="usuario" placeholder="ejemplo@gmail.com" required>

                    <label for="password">Contraseña</label>
                    <input type="password" name="password" id="password" placeholder="Contraseña" required>

                    <button type="submit" class="submit-btn">Iniciar Sesión </button>
                    <a href="registro.php" class="registrar">Registrarse</a>
                </form>
            </div>
        </div>
        </section>
        <footer>
            <div class="navbar navbar-fixed-bottom fot">
                <a href=""><img src="img/iconoFacebook.png" class="icon"></a>
                <a href=""><img src="img/iconoInstagram.png" class="icon"></a>
                <a href=""><img src="img/iconoYoutube.png" class="icon"></a>
                <a href="#"><img src="img/iconoUbicacion.png" class="icon"></a>
                <a href="#"><img src="img/iconoContacto.png" class="icon"></a>
            </div>
            <div class="commons">
                <p xmlns:cc="http://creativecommons.org/ns#" ><a href="https://creativecommons.org/licenses/by-nc-nd/4.0/?ref=chooser-v1" target="_blank" rel="license noopener noreferrer" style="display:inline-block;"><img style="height:32px!important;margin-left:3px;vertical-align:text-bottom;" src="https://mirrors.creativecommons.org/presskit/icons/cc.svg?ref=chooser-v1" alt=""><img style="height:32px!important;margin-left:3px;vertical-align:text-bottom;" src="https://mirrors.creativecommons.org/presskit/icons/by.svg?ref=chooser-v1" alt=""><img style="height:32px!important;margin-left:3px;vertical-align:text-bottom;" src="https://mirrors.creativecommons.org/presskit/icons/nc.svg?ref=chooser-v1" alt=""><img style="height:32px!important;margin-left:3px;vertical-align:text-bottom;" src="https://mirrors.creativecommons.org/presskit/icons/nd.svg?ref=chooser-v1" alt=""></a></p>
            </div>
        </footer>
    </body>
</html>