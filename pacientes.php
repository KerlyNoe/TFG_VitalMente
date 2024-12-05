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
            <!-- Contenedor para usuario y servicios -->
            <div class="contenedor-cabecera">
                <!-- Nombre del usuario -->
                <?php 
                    $query = "SELECT nombre, primer_apellido FROM usuarios 
                              WHERE id_usuarios = ?";
                    if ($stmt = $conn->prepare($query)) {
                        $stmt->bind_param('i', $id_usuario);
                        $stmt->execute();
                        $usuario = $stmt->get_result();

                        if ($usuario && $usuario->num_rows > 0) {
                            $usuarios = $usuario->fetch_assoc();
                            echo '<div class="seccion-usuario text-center">';
                            echo '<h2> Bienvenid@, ' . htmlspecialchars($usuarios['nombre']) . ' ' . htmlspecialchars($usuarios['primer_apellido']) .'</h2>';
                            echo '</div>';
                        } else {
                            echo "Error: No se pudo encontrar el usuario.";
                        }
                        $stmt->close();
                    } else {
                        echo "Error de consulta: " . $conn->error;
                    }
                ?>
            </div>
            
            <div class="contenido-central">
                <!-- Servicios -->
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                    <?php
                        $query = "SELECT * FROM servicios WHERE estado != 'eliminado'";
                        $stmt = $conn->query($query);
                        if($stmt->num_rows > 0){
                            while($servicios = $stmt->fetch_assoc()){
                                ?>
                                <div class="col">
                                    <div class="card h-100 shadow-sm">
                                        <h5 class="card-title text-center text-warning"><?= $servicios['nombre_servicio']; ?></h5>
                                        <div class="card-body">
                                            <p class="card-text text-dark"><?= $servicios['descripcion']; ?></p>
                                        </div>
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item precio text-danger"><?= $servicios['precio']; ?> €</li>
                                        </ul>
                                        <div class="card-footer text-center">
                                            <form action="insertarCita_formulario.php" method="POST">
                                                <input type="hidden" name="id_servicio" value="<?= htmlspecialchars($servicios['id_servicios']); ?>">
                                                <button type="submit" class="btn btn-info w-100">Seleccionar</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                        } else {
                            echo "Servicios no disponibles";
                        }
                    ?>
                </div>

                <!-- Formulario para cancelar la cita -->
                <?php
                    $query = "SELECT id_citas, fecha FROM citas WHERE id_usuarios = ? AND estado = 'reservada'"; // Cambié 'reservadas' por 'reservada'
                    if ($stmt = $conn->prepare($query)) {
                        $stmt->bind_param('i', $id_usuario); // $id_usuario debe ser el ID del usuario actual
                        $stmt->execute();
                        $citas = $stmt->get_result();

                        if ($citas && $citas->num_rows > 0) {
                            echo '<div class="seccion-cancelar mt-5">';
                            echo '<form action="cancelar_cita.php" method="post">';
                            echo '<label for="citas">Cancelar Citas</label>';
                            echo '<select name="id_citas" id="citas" class="form-select mb-2">';
                            echo '<optgroup label="Citas Próximas">';
                            
                            // Mostrar las citas disponibles
                            while ($cita = $citas->fetch_assoc()) {
                                echo '<option value="' . htmlspecialchars($cita['id_citas']) . '">' . htmlspecialchars($cita['fecha']) . '</option>';
                            }

                            echo '</optgroup>';
                            echo '</select>';
                            echo '<button type="submit" class="btn btn-danger w-100">Cancelar</button>';
                            echo '</form>';
                            echo '</div>';
                        } else {
                            echo '<p class="mt-5">No tienes citas reservadas para cancelar.</p>';
                        }
                        $stmt->close();
                    } else {
                        echo "Error de consulta: " . htmlspecialchars($conn->error); // Escapar el error por seguridad
                    }
                ?>

                <!-- Resumen de la cita -->
                <h1 class="text-center mb-4">Resumen</h1>
                <?php
                    $query = "SELECT 
                            usuarios.nombre AS profesional,
                            servicios.nombre_servicio AS servicio,
                            citas.fecha,
                            medicamentos_citas.nombre_medicamento AS medicamento,
                            medicamentos_citas.dosis AS dosis,
                            medicamentos_citas.instrucciones AS instrucciones,
                            actividades_citas.descripcion AS actividad
                        FROM citas
                        LEFT JOIN profesionales
                        ON citas.id_profesionales = profesionales.id_profesionales
                        LEFT JOIN usuarios 
                        ON profesionales.id_usuario = usuarios.id_usuarios
                        LEFT JOIN servicios 
                        ON citas.id_servicios = servicios.id_servicios
                        LEFT JOIN medicamentos_citas
                        ON medicamentos_citas.id_citas = citas.id_citas
                        LEFT JOIN actividades_citas 
                        ON actividades_citas.id_citas = citas.id_citas
                        WHERE citas.id_usuarios = ?
                        ORDER BY citas.fecha DESC";

                    if ($stmt = $conn->prepare($query)) {
                        $stmt->bind_param('i', $id_usuario); // ID del usuario actual
                        $stmt->execute();
                        $result = $stmt->get_result();

                        if ($result && $result->num_rows > 0) {
                            // Mostrar resultados
                            while ($row = $result->fetch_assoc()) {
                                echo "<div class='mb-3'>" . 
                                    htmlspecialchars($row['profesional']) . " - " . 
                                    htmlspecialchars($row['servicio']) . " - " . 
                                    htmlspecialchars($row['fecha']) . " - " . 
                                    htmlspecialchars($row['medicamento'] ?? 'N/A') . " - " .  
                                    htmlspecialchars($row['dosis'] ?? 'N/A') . " - " . 
                                    htmlspecialchars($row['instrucciones'] ?? 'N/A') . " - " .
                                    htmlspecialchars($row['actividad'] ?? 'N/A') . 
                                    "</div>";
                            }
                        } else {
                            echo "<p>Tu resumen estará disponible cuando tengas una cita.</p>";
                        }

                        $stmt->close();
                    } else {
                        echo "Error en la consulta: " . htmlspecialchars($conn->error);
                    }
                ?>
            </div>
        </div>
    </body>
</html>
<?php include("footer/footer.php"); ?>