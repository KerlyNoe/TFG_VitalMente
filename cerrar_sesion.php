<?php
    session_start();
    session_unset(); 
    
    //Destruir la sesión
    session_destroy();

    // Redirige a la página de acceder
    header("Location: acceder.php");
    exit();
?>