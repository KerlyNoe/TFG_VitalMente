<?php
    //Verificar si hay una sesión activa.
    if(session_status() === PHP_SESSION_NONE){
        session_start();
    }

    //Configuración de conexión con la base de datos.
    include_once("config.php");
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <link rel="stylesheet" href="css/estiloshf.css">
    </head>
    <body>
        <!-- Navegador -->
        <header>
            <div class="logo">
                <img src="img/logo.png" alt="logo">
            </div>
            <nav class="nav">
                <?php
                    if(isset($_SESSION['id_usuarios'])) {
                        $query = "SELECT tipo_usuario FROM usuarios
                                  WHERE id_usuarios = ?";
                        $stmt = $conn->prepare($query);
                        $stmt->bind_param('i', $_SESSION['id_usuarios']);
                        $stmt->execute();
                        $tmp = $stmt->get_result();
                        $usuario = $tmp->fetch_assoc();
                        $tipo_usuario = $usuario['tipo_usuario'];
                            if($tipo_usuario === 'administrador') {
                                echo '<a href="insertar_actividades.php">Editar Eventos</a>';
                                echo '<a href="cerrar_sesion.php">Cerrar Sesion</a>';
                            }elseif($tipo_usuario === 'profesional') {
                                echo '<a href="cerrar_sesion.php">Cerrar Sesion</a>';
                            }elseif($tipo_usuario === 'normal') { //Desde Perfil
                                echo '<a href="inicio.php">Inicio</a>';
                                echo '<a href="actividades.php">Actividades</a>';
                                echo '<a href="cerrar_sesion.php">Cerrar Sesion</a>';
                            }
                        $stmt->close();
                    }else {
                        echo '<a href="servicios.php">Servicios</a>';
                        echo '<a href="contacto.php">Contacto</a>';
                        echo '<a href="actividades.php">Actividades</a>';
                        echo '<a href="acceder.php">Acceder</a>';
                    }
                ?>
            </nav>
        </header>
    </body>
</html>