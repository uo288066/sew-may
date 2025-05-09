<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.html");
    exit;
}

require_once "DB.php";
require_once "Recurso.php";

$db = new DB();
$conexion = $db->getConexion();

// Modificamos la consulta para obtener el tipo de recurso desde la tabla categoria
$query = "SELECT r.rid, r.nombre, c.nombre AS tipo, r.descripcion, r.precio, r.plazas, r.fecha_inicio, r.fecha_fin 
          FROM recurso r
          JOIN categoria c ON r.cid = c.cid";

$result = $conexion->query($query);
$recursos = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Reservas</title>
    <link rel="icon" type="image/x-icon" href="multimedia/img/favicon.ico">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../estilo/estilo.css">
    <link rel="stylesheet" href="../estilo/layout.css">
</head>
<body>
    <header>
        <h1><a href="../index.html">CARAVIA</a></h1>
        <nav>
            <a href="../index.html">Inicio</a>
            <a href="../gastronomia.html">Gastronomía</a>
            <a href="../rutas.html">Rutas</a>
            <a href="../meteorologia.html">Meteorología</a>
            <a href="../juego.html">Juego</a>
            <a class="activo" href="reservas.php">Reservas</a>
            <a href="../ayuda.html">Ayuda</a>
        </nav>
    </header>
    <p>Estás en <a href="index.html">Inicio</a> | Reservas</p>

    <!-- Enlace a la página de usuario -->
    <p><a href="procesar_usuario.php">Ver mis reservas y gestionar anulaciones</a></p>

    <h2>Recursos turísticos disponibles</h2>
        <form action="procesar_reserva.php" method="POST">
            <table border="1" cellpadding="8">
                <thead>
                    <tr>
                        <th>Seleccionar</th>
                        <th>Nombre</th>
                        <th>Tipo</th>
                        <th>Descripción</th>
                        <th>Precio (€)</th>
                        <th>Plazas</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recursos as $r): ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="reservas[]" value="<?= $r['rid'] ?>-<?= $r['precio'] ?>" data-precio="<?= $r['precio'] ?>" onchange="actualizarPrecios()">
                            </td>
                            <td><?= htmlspecialchars($r['nombre']) ?></td>
                            <td><?= htmlspecialchars($r['tipo']) ?></td>
                            <td><?= htmlspecialchars($r['descripcion']) ?></td>
                            <td><?= htmlspecialchars($r['precio']) ?></td>
                            <td><?= htmlspecialchars($r['plazas']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <br>
            <label for="presupuesto">Presupuesto total (€):</label>
            <input type="number" name="presupuesto_total" id="presupuesto" step="0.01" min="0" readonly required>

            <br><br>
            <button type="submit">Confirmar Reservas</button>
        </form>

    <script>
        const checkboxes = document.querySelectorAll("input[name='reservas[]']");
        const presupuestoInput = document.getElementById("presupuesto");

        function actualizarPrecios() {
            let total = 0;
            checkboxes.forEach(cb => {
                if (cb.checked) {
                    total += parseFloat(cb.dataset.precio);
                }
            });
            presupuestoInput.value = total.toFixed(2);
        }

        checkboxes.forEach(cb => {
            cb.addEventListener("change", actualizarPrecios);
        });

        actualizarPrecios(); // Ejecutar al cargar
    </script>

    <script>
        const checkboxes = document.querySelectorAll("input[name='recursos_id[]']");
        const presupuestoInput = document.getElementById("presupuesto");

        checkboxes.forEach(checkbox => {
            checkbox.addEventListener("change", () => {
                let total = 0;
                checkboxes.forEach(cb => {
                    if (cb.checked) {
                        total += parseFloat(cb.dataset.precio);
                    }
                });
                presupuestoInput.value = total.toFixed(2);
            });
        });
    </script>
</body>
</html>
