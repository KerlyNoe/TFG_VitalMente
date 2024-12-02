<?php
    if (isset($_POST['nombre'], $_POST['email'], $_POST['mensaje'])) {
        $nombre = $_POST['nombre'];
        $email = $_POST['email'];
        $mensaje = $_POST['mensaje'];

        // Limpiar campos
        $nombre = trim(htmlspecialchars($nombre));
        $email = trim(htmlspecialchars($email));
        $mensaje = htmlspecialchars($mensaje);


        // Evitar caracteres peligrosos del correo.
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $to = "infovitalmenteclinica@gmail.com"; 
                $subject = "Nuevo mensaje de contacto";
                $body = "Nombre: $nombre\nCorreo: $email\nMensaje:\n$mensaje";
                $headers = "From: no-reply@vitalmenteclinica.es\r\nReply-To: $email";

                if (mail($to, $subject, $body, $headers)) {
                    $mensaje = "Mensaje enviado con éxito.";
                    $tipo = "alert-success";
                    header("Location: contacto.php?mensaje=" . urlencode($mensaje) . "&tipo=" . urlencode($tipo));
                    exit();
                } else {
                    $mensaje = "Error al enviar el mensaje";
                    $tipo = "alert-danger";
                    header("Location: contacto.php?mensaje=" . urlencode($mensaje) . "&tipo=" . urlencode($tipo));
                    exit();
                }
            } else {
                $mensaje = "Correo electrónico inválido";
                $tipo = "alert-danger";
                header("Location: contacto.php?mensaje=" . urlencode($mensaje) . "&tipo=" . urlencode($tipo));
                exit();
            }
    }else {
        $mensaje = "Campos incompletos";
        $tipo = "alert-danger";
        header("Location: contacto.php?mensaje=" . urlencode($mensaje) . "&tipo=" . urlencode($tipo));
        exit();
    }
?>
