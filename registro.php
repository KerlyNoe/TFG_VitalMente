<?php
    include_once('config.php');
    include_once("header/navegadorCinco.php");

    $mensaje = '';
    $tipo = '';

    if($_SERVER['REQUEST_METHOD'] === "POST"){
        $usuario = $_POST['nombre'];
        $primer_apellido = $_POST['primer_apellido'];
        $segundo_apellido = $_POST['segundo_apellido'];
        $email = $_POST['mail'];
        $contrasenia = $_POST['password'];
        $telefono = $_POST['telefono'];

        //Limpiar campos
        $usuario = trim(htmlspecialchars($usuario));
        $primer_apellido = trim(htmlspecialchars($primer_apellido));
        $segundo_apellido = trim(htmlspecialchars($segundo_apellido));
        $email = trim(htmlspecialchars($email));
        $contrasenia = htmlspecialchars($contrasenia);
        $telefono = trim(htmlspecialchars($telefono));

        //Convertir a mínuscula los datos 
        $usuario = strtolower($usuario);
        $primer_apellido = strtolower($primer_apellido);
        $segundo_apellido = strtolower($segundo_apellido);
        $email = strtolower($email);

            if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
                if(strlen($contrasenia) >= 8) {
                    $contrasenia_hash = password_hash($contrasenia, PASSWORD_DEFAULT);
                    if(preg_match('/^\d{9}$/', $telefono)) {
                        $query = "INSERT INTO usuarios (nombre,primer_apellido,segundo_apellido,email,telefono,contrasena) VALUES (?,?,?,?,?,?)";
                        $stmt = $conn->prepare($query);
                        $stmt->bind_param('ssssss',$usuario, $primer_apellido, $segundo_apellido, $email, $telefono,$contrasenia_hash);
                        if($stmt->execute()){
                            $mensaje = "Usuario creado correctamente";
                            $tipo = "exito";
                            header("refresh: 1; url = acceder.php");
                            exit();
                        }else{
                            $mensaje = "El usuario no ha sido creado";
                            $tipo = "error";
                        }
                    }else {
                        $mensaje = "El número de télefono no es válido";
                        $tipo = "error";
                    }
                }else {
                    $mensaje = "La contraseña debe tener al menos 8 carácteres";
                    $tipo = "error";
                }
            }else {
                $mensaje = "Formato no válido: correo electrónico";
                $tipo = "error";
            }
        $stmt->close();
    }
?>
<!DOCTYPE html>
<html lan="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Clínica Vitalmente | Registro</title>
        <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
        <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
        <link rel="stylesheet" href="css/estilos.css">
    </head>
    <body>
        <!-- Para mostrar las notificaciones-->
        <?php if($mensaje): ?>
            <div id="notificacion" class="notificacion <?= $tipo; ?>">
                <?= $mensaje; ?>
            </div>
        <?php endif; ?>
        <section>
            <div class="container">
                <div class="derecha">
                    <img src="img/secundario.png" alt="registro" class="imagen">
                </div>
                <div class="izquierda">
                    <h1>Registrarse</h1>
                    <form action="" method="post">
                        <label for="nombre">Nombre</label>
                        <input type="text" name="nombre" id="nombre" required>

                        <label for="primer_apellido">Primer apellido</label>
                        <input type="text" name="primer_apellido" id="primer_apellido" required>
                        
                        <label for="segundo_apellido">Segundo apellido</label>
                        <input type="text" name="segundo_apellido" id="segundo_apellido">

                        <label for="mail">Correo electrónico</label>
                        <input type="email" name="mail" id="mail" placeholder="ejemplo@gmail.com" required>

                        <label for="password">Contraseña</label>
                        <input type="password" name="password" id="password" minlength="8" required>

                        <label for="telefono">Teléfono</label>
                        <input type="tel" name="telefono" id="telefono" minlength="9" maxlength="9" required>

                        <button type="submit" class="submit-btn">Registrarse</button>
                        <a href="acceder.php" class="registrar">Iniciar sesión</a>
                    </form>
                </div>
            </div>
        </section>
        <!-- Script para que desaparezcan las notificaciones-->
        <script>
            window.onload = function(){
                const notificacion = document.getElementById('notificacion');
                if(notificacion){
                    setTimeout(() => {
                        notificacion.style.opacity = '0';
                    }, 600);
                    setTimeout(() => {
                        notificacion.remove();
                    }, 4500)
                }
            };    
        </script>
    </body>
</html>
<?php include_once("footer/footer.php"); ?>
