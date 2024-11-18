<?php
    session_start();
    session_unset(); 
    //Destruir la sesión
    session_destroy();
    header("Location: acceder.php");
    exit();
?>