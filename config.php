<?php
    const LOCALHOST = "localhost";
    const USER = "noemy2";
    const PASSWORD = "gestion123";
    const DATABASE = "gestion_clinica";

    //Conexión a la base de datos
    $conn = new mysqli(LOCALHOST,USER,PASSWORD,DATABASE);
        if($conn->connect_errno){
            echo "Fallo en la conexion " . $conn->connect_error;
    }

?>
