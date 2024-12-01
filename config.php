<?php
    const LOCALHOST = "******";
    const USER = "*****";
    const PASSWORD = "******";
    const DATABASE = "******";

    //Conexión a la base de datos
    $conn = new mysqli(LOCALHOST,USER,PASSWORD,DATABASE);
        if($conn->connect_errno){
            die("Fallo en la conexion " . $conn->connect_error);
    }
?>
