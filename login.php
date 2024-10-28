<?php
if(session_status() === PHP_SESSION_NONE){
    session_start();
}
include_once("config.php");

    if(isset($_POST['usuario']) && isset($_POST['password'])){
        $usuario = $_POST['usuario'];
        $contrasenia = $_POST['password'];

        //Limpiar los campos.
        $usuario = trim(htmlspecialchars($usuario));
        $contrasenia = htmlspecialchars($contrasenia);

        //Convertir usuario a mayúscula 
        $usuario = strtoupper($usuario);

        //Query:
        $query = "SELECT id_usuarios, nombre,  email, contrasena, tipo_usuario  FROM usuarios
                    WHERE  email = ?";
        $stmt = $conn->prepare($query);
        if(!$stmt){
            echo "Error en la consulta: " . $conn->error;
            exit();
        }
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $almacenado = $stmt->get_result();
            if($almacenado->num_rows > 0 ){
                $login = $almacenado->fetch_assoc();

                //Verificar la contraseña
                if(password_verify($contrasenia, $login['contrasena'])){
                    $_SESSION['id_usuarios'] = $login['id_usuarios'];
                    $_SESSION['email'] = $login['email'];
                    $_SESSION['tipo_usuario'] = $login['tipo_usuario'];

                    if($login['tipo_usuario'] === 'administrador'){
                    echo '<script language="JavaScript">';
                    echo 'alert("Bienvenido");';
                        echo 'window.location.href = "admin.php";';
                    echo "</script>";
                    }elseif ($login['tipo_usuario'] === 'profesional') {
                        echo '<script language="JavaScript">';
                            echo 'alert("Bienvenido");';
                            echo 'window.location.href = "profesionales.php";';
                        echo "</script>";
                    }else {
                        echo '<script language="JavaScript">';
                        echo 'alert("Bienvenido");';
                            echo 'window.location.href = "servicios.php";';
                        echo "</script>";
                    }
                    
                }else {
                    echo '<script language="JavaScript">';
                        echo 'alert("Usuario y contraseña incorrecta")';
                        echo 'window.location.href = "acceder.php";';
                    echo "</script>";
                }
            } 
        $stmt->close();       
    }
?>