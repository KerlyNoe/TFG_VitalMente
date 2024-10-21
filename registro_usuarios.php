<?php
    include_once('config.php');
    
        if(isset($_POST['nombre']) && isset($_POST['primer_apellido']) && isset($_POST['mail']) && isset($_POST['password']) && isset($_POST['telefono'])){
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

                if(strlen($contrasenia) >= 8){
                    $contrasenia_hash = password_hash($contrasenia, PASSWORD_DEFAULT);

                    $query = "INSERT INTO usuarios (nombre,primer_apellido,segundo_apellido,email,telefono,contrasena) VALUES (?,?,?,?,?,?)";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param('ssssss',$usuario, $primer_apellido, $segundo_apellido, $email, $telefono,$contrasenia_hash);
                    if($stmt->execute()){
                        echo '<script language="JavaScript">';
                            echo 'alert("Usuario creado correctamente");';
                            echo 'window.location.href = "acceder.php";';
                        echo "</script>";
                    }else{
                        echo '<script language="JavaScript">';
                            echo 'alert("El usuario no ha sido creado");';
                            echo 'window.location.href = "registro.php";';
                        echo "</script>";
                    }
                }else {
                    echo '<script language="JavaScript">';
                        echo 'alert("La contraseña debe tener al menos 8 carácteres");';
                        echo 'window.location.href = "registro.php";';
                    echo "</script>";
                }
            $stmt->close();
        }
?>