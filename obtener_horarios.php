<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Incluye la configuración de la base de datos
    include_once('config.php');

    // Verifica si el usuario está autenticado
    if (!isset($_SESSION['tipo_usuario'])) {
        header("Location: acceder.php");
        exit();
    }

    // Valida que la solicitud incluye los parámetros necesarios
    if (!isset($_GET['fecha']) || !isset($_GET['id_profesional'])) {
        echo "<tr><td colspan='3' class='text-center'>Parámetros faltantes: fecha o profesional.</td></tr>";
        exit();
    }

    $fecha = $_GET['fecha'];
    $id_profesional = (int)$_GET['id_profesional'];

    try {
        // Consulta los horarios disponibles del profesional para la fecha seleccionada
        $sql = "SELECT horarios.hora_inicio, horarios.hora_fin,
                    CASE
                        WHEN EXISTS (
                            SELECT 1 FROM citas 
                            WHERE citas.id_profesionales = horarios.id_profesionales
                                AND citas.fecha = ?
                                AND citas.hora_inicio = horarios.hora_inicio
                                AND citas.estado IN ('reservada', 'pendiente')
                        ) THEN 'reservada'
                        ELSE 'disponible'
                    END AS estado
                FROM horarios
                WHERE horarios.id_profesionales = ?
                ORDER BY horarios.hora_inicio ASC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $fecha, $id_profesional);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $estado = $row['estado'] === 'reservada' ? 'No disponible' : 'Disponible';
                $seleccion = $row['estado'] === 'reservada'
                    ? '<span class="text-muted">No disponible</span>'
                    : "<input type='radio' name='hora_inicio' value='{$row['hora_inicio']}' required>";

                echo "<tr>
                        <td>{$row['hora_inicio']} - {$row['hora_fin']}</td>
                        <td>$estado</td>
                        <td>$seleccion</td>
                    </tr>";
            }
        } else {
            echo "<tr><td colspan='3' class='text-center'>No se encontraron horarios disponibles.</td></tr>";
        }

        $stmt->close();
    } catch (Exception $e) {
        echo "<tr><td colspan='3' class='text-center'>Error al obtener horarios: " . htmlspecialchars($e->getMessage()) . "</td></tr>";
    }
?>
