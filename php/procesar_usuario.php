<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.html");
    exit;
}

require_once "DB.php";

// Conectar a la base de datos
$db = new DB();
$conexion = $db->getConexion();

// Obtener las reservas actuales del usuario
$usuario_id = $_SESSION['usuario_id'];
$query_reservas = "
    SELECT r.reid, res.nombre AS recurso, r.fecha_reserva, r.presupuesto, r.estado
    FROM reserva r
    JOIN recurso res ON r.rid = res.rid
    WHERE r.uuid = ? AND r.estado != 'cancelada'
";
$stmt_reservas = $conexion->prepare($query_reservas);
$stmt_reservas->bind_param("i", $usuario_id);
$stmt_reservas->execute();
$result_reservas = $stmt_reservas->get_result();

// Obtener el historial de anulaciones del usuario
$query_anulaciones = "
    SELECT ra.fecha_anulacion, res.nombre AS recurso, ra.motivo
    FROM historial_anulaciones ra
    JOIN reserva r ON ra.reid = r.reid
    JOIN recurso res ON r.rid = res.rid
    WHERE r.uuid = ?
";
$stmt_anulaciones = $conexion->prepare($query_anulaciones);
$stmt_anulaciones->bind_param("i", $usuario_id);
$stmt_anulaciones->execute();
$result_anulaciones = $stmt_anulaciones->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Mi Usuario - Reservas</title>
    <link rel="icon" type="image/x-icon" href="multimedia/img/favicon.ico">
    <link rel="stylesheet" href="../estilo/estilo.css">
    <link rel="stylesheet" href="../estilo/layout.css">
</head>
<body>
    <header>
        <h1><a href="../index.html">Caravia</a></h1>
        <nav>
            <a href="../index.html">Inicio</a>
            <a href="../gastronomia.html">Gastronomía</a>
            <a href="../rutas.html">Rutas</a>
            <a href="../meteorologia.html">Meteorología</a>
            <a href="../juego.html">Juego</a>
            <a class="activo" href="usuario.php">Mi Usuario</a>
            <a href="../ayuda.html">Ayuda</a>
        </nav>
    </header>

    <p>Estás en <a href="../index.html">Inicio</a> | <a href="reservas.php">Reservas</a> | Mis Reservas y Anulaciones</p>

    <h2>Mis Reservas</h2>

    <h3>Reservas Actuales</h3>
    <table border="1" cellpadding="8">
        <thead>
            <tr>
                <th>Recurso</th>
                <th>Fecha Reserva</th>
                <th>Presupuesto</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($reserva = $result_reservas->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($reserva['recurso']) ?></td>
                    <td><?= htmlspecialchars($reserva['fecha_reserva']) ?></td>
                    <td><?= htmlspecialchars($reserva['presupuesto']) ?>€</td>
                    <td><?= htmlspecialchars($reserva['estado']) ?></td>
                    <td>
                        <?php if ($reserva['estado'] != 'cancelada'): ?>
                            <form action="procesar_anulacion.php" method="POST" onsubmit="return confirmarAnulacion()">
                                <input type="hidden" name="reid" value="<?= $reserva['reid'] ?>">
                                <button type="submit" name="anular">Anular Reserva</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    
    <h3>Historial de Anulaciones</h3>
    <table border="1" cellpadding="8">
        <thead>
            <tr>
                <th>Recurso</th>
                <th>Fecha Anulación</th>
                <th>Motivo</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($anulacion = $result_anulaciones->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($anulacion['recurso']) ?></td>
                    <td><?= htmlspecialchars($anulacion['fecha_anulacion']) ?></td>
                    <td><?= htmlspecialchars($anulacion['motivo']) ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <script>
        function confirmarAnulacion() {
            return confirm("¿Estás segura o seguro de que deseas anular esta reserva?");
        }
    </script>
</body>
</html>

<?php
$db->cerrar();
?>
