<?php include_once("header/navegadorInicio.php"); ?>

<!DOCTYPE html>
<html lan="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Registro</title>
        <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
        <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
        <link rel="stylesheet" href="css/estilos.css">
    </head>
    <body>
        <!--<header>
            <div class="logo">
                <img src="img/logo.png" alt="logo">
            </div>
            <nav class="nav">
                <a href="inicio.php"> Inicio </a>
                <a href="contacto.php"> Contacto </a>
                <a href="servicios.php"> Servicios </a>
                <a href="actividades.php"> Actividades </a>
            </nav>
        </header>-->
        <section>
            <div class="container">
                <div class="derecha">
                    <img src="img/imagen11.jpg" alt="registro" class="imagen">
                </div>
                <div class="izquierda">
                    <h1>Registrarse</h1>
                    <form action="registro_usuarios.php" method="post">
                        <label for="nombre">Nombre</label>
                        <input type="text" name="nombre" id="nombre" size="50" required>

                        <label for="primer_apellido">Primer apellido</label>
                        <input type="text" name="primer_apellido" id="primer_apellido" size="50" required>
                        
                        <label for="segundo_apellido">Segundo apellido</label>
                        <input type="text" name="segundo_apellido" id="segundo_apellido" size="50">

                        <label for="mail">Correo electrónico</label>
                        <input type="email" name="mail" id="mail" size="50" placeholder="ejemplo@gmail.com" required>

                        <label for="password">Contraseña</label>
                        <input type="password" name="password" id="password" minlength="8" size="50" required>

                        <label for="telefono">Teléfono</label>
                        <input type="tel" name="telefono" id="telefono" minlength="9" maxlength="9" required>

                        <button type="submit" class="submit-btn">Registrarse</button>
                        <a href="acceder.php" class="registrar">Iniciar sesión</a>
                    </form>
                </div>
            </div>
        </section>
        <!--<footer>
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
        </footer>-->
        <?php include_once("footer/footer.php"); ?>
    </body>
</html>