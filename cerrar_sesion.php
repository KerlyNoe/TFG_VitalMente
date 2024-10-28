<?php
session_start();
    unset($_SESSION['usuario']);

    echo '<script language="JavaScript">';
        echo 'alert("Sesion cerrada correctamente");';
        echo 'window.location.href = "iniciar-sesion.php";';
    echo '</script>';
?>