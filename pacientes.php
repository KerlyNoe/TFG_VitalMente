<?php 
// Verifica si hay una sesión activa.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Configuración de conexión con la base de datos.
include_once("config.php");

if (!isset($_SESSION['tipo_usuario'])) {
    header("Location: acceder.php");
    exit();
}

$tipo_usuario = $_SESSION['tipo_usuario'];
$id_usuario = $_SESSION['id_usuarios']; 

// Incluye el header.
include_once("header/navegadorPrimario.php");
?> 
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Clínica VitalMente | Perfil</title>
        <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
        <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
        <link rel="stylesheet" href="css/estilosPacientes.css">
    </head>
    <body>
        <div class="contenedor">
            <!-- Nombre del usuario -->
            <?php 
                // Consultar nombre del usuario
                $query = "SELECT nombre, primer_apellido FROM usuarios WHERE id_usuarios = ?";
                if ($stmt = $conn->prepare($query)) {
                    $stmt->bind_param('i', $id_usuario);
                    $stmt->execute();
                    $usuario = $stmt->get_result();

                    if ($usuario && $usuario->num_rows > 0) {
                        $usuarios = $usuario->fetch_assoc();
                        echo '<div class="seccion-usuario">';
                        echo '<div class="nombre">' . htmlspecialchars($usuarios['nombre']) . ' ' . htmlspecialchars($usuarios['primer_apellido']) .'</div>';
                        echo '</div>';
                    } else {
                        echo "Error: No se pudo encontrar el usuario.";
                    }
                    $stmt->close();
                } else {
                    echo "Error de consulta: " . $conn->error;
                }
            ?>

            <!-- Selección de servicios -->
            <?php
                $query2 = "SELECT id_servicios, nombre_servicio FROM servicios";
                    if ($stmt2 = $conn->query($query2)) {
                        if ($stmt2->num_rows > 0) {
                            echo '<div class="seccion-servicio">';
                            echo '<select name="servicio" id="servicio">';
                            echo '<optgroup label="Servicios">';
                            while ($servicios = $stmt2->fetch_assoc()) {
                                echo '<option value="' . htmlspecialchars($servicios['id_servicios']) . '">' . htmlspecialchars($servicios['nombre_servicio']) . '</option>';
                            }
                            echo '</optgroup>';
                            echo '</select>';
                            echo '</div>';
                        } else {
                            echo "No se encontraron servicios disponibles.";
                        }
                    } else {
                        echo "Error de consulta: " . $conn->error;
                    }
            ?>
            <br>
            <!-- Próximas citas del usuario -->
            <?php
                $query = "SELECT citas.fecha, citas.id_citas, profesionales.nombre AS profesional 
                          FROM citas 
                          INNER JOIN profesionales ON citas.id_profesionales = profesionales.id_profesionales
                          WHERE citas.id_usuarios = ? AND citas.estado = 'reservadas'";

                    if ($stmt = $conn->prepare($query)) {
                        $stmt->bind_param('i', $id_usuario);
                        $stmt->execute();
                        $citas = $stmt->get_result();

                        echo '<h1>Próximas Citas:</h1>';
                        if ($citas && $citas->num_rows > 0) {
                            echo '<ul>';
                            while ($cita = $citas->fetch_assoc()) {
                                echo '<li>' . htmlspecialchars($cita['fecha']) . ' - ' . htmlspecialchars($cita['profesional']) . '</li>';
                            }
                            echo '</ul>';
                        } else {
                            echo '<div>';
                            echo '<p>No tienes citas pendientes</p>';
                            echo '</div>';
                        }
                        $stmt->close();
                    } else {
                        echo "Error de consulta: " . $conn->error;
                    }
            ?>

            <!-- Calendario de citas -->
            <div id="calendario">
                <script>
                    // funciones del calendario
                </script>
            </div>

            <!-- Formulario para cancelar la cita -->
            <?php
                $query = "SELECT id_citas, fecha FROM citas WHERE id_usuarios = ? AND estado = 'reservadas'";
                    if ($stmt = $conn->prepare($query)) {
                        $stmt->bind_param('i', $id_usuario);
                        $stmt->execute();
                        $citas = $stmt->get_result();
                        if ($citas && $citas->num_rows > 0) {
                            echo '<div class="seccion-cancelar">';
                            echo '<form action="cancelar_cita.php" method="post">';
                            echo '<label for="cancelar_cita">Cancelar Citas</label>';
                            echo '<select name="id_citas" id="citas">';
                            echo '<optgroup label="Citas Próximas">';
                            while ($cita = $citas->fetch_assoc()) {
                                echo '<option value="' . htmlspecialchars($cita['id_citas']) . '">' . htmlspecialchars($cita['fecha']) . '</option>';
                            }
                            echo '</optgroup>';
                            echo '</select>';
                            echo '<button type="submit">Cancelar</button>';
                            echo '</form>';
                            echo '</div>';
                        } else {
                            echo '<div>';
                            echo '<p>No tienes citas reservadas para cancelar.</p>';
                            echo '</div>';
                        }
                        $stmt->close();
                    } else {
                        echo "Error de consulta: " . $conn->error;
                    }
            ?>

            <!-- Resumen de las citas -->
            <div>
                <h1>Resumen</h1>
                <?php
                    $query = "SELECT citas.fecha, profesionales.nombre AS profesional, medicamentos_citas.nombre_medicamento, medicamentos_citas.instrucciones, medicamentos_citas.dosis, actividades_citas.descripcion, profesionales.telefono
                            FROM citas
                            INNER JOIN profesionales ON citas.id_profesionales = profesionales.id_profesionales
                            LEFT JOIN medicamentos_citas ON citas.id_citas = medicamentos_citas.id_citas
                            LEFT JOIN actividades_citas ON citas.id_citas = actividades_citas.id_citas
                            WHERE citas.id_usuarios = ?";
                        if ($stmt = $conn->prepare($query)) {
                            $stmt->bind_param('i', $id_usuario);
                            $stmt->execute();
                            $citas = $stmt->get_result();

                            if ($citas && $citas->num_rows > 0) {
                                echo '<table border="1">';
                                echo "<tr>"; 
                                echo "<th>Profesional</th>";
                                echo "<th>Fecha</th>";
                                echo "<th>Medicamentos</th>";
                                echo "<th>Recomendaciones</th>";
                                echo "</tr>";
                                while ($cita = $citas->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($cita['profesional']) . "</td>";
                                    echo "<td>" . htmlspecialchars($cita['fecha']) . "</td>";
                                    echo "<td>" . htmlspecialchars($cita['nombre_medicamento'] . " " . $cita['dosis'] . " " . $cita['instrucciones']) . "</td>";
                                    echo "<td>" . htmlspecialchars($cita['descripcion']) . "</td>";
                                    echo "</tr>";
                                }
                                echo '</table>';
                            } else {
                                echo '<div>';
                                echo '<p>No has tenido citas aún</p>';
                                echo '</div>';
                            }
                            $stmt->close();
                        } else {
                            echo "Error de consulta: " . $conn->error;
                        }
                ?>
            </div>
        </div>
    </body>
</html>
<?php include("footer/footer.php"); ?>
