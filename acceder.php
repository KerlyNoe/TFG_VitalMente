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
        <?php include_once("header/navegadorServicios.php"); ?>
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
        <?php include_once("footer/footer.php"); ?>
    </body>
</html>