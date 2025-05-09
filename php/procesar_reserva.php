<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.html");
    exit;
}

require_once "DB.php";

$db = new DB();
$conexion = $db->getConexion();

if (isset($_POST['reservas']) && is_array($_POST['reservas'])) {
    $usuario_id = $_SESSION['usuario_id'];
    $conexion->begin_transaction();

    try {
        foreach ($_POST['reservas'] as $reserva) {
            list($rid, $precio) = explode('-', $reserva);
            $rid = (int)$rid;
            $precio = (float)$precio;

            $stmt = $conexion->prepare("INSERT INTO reserva (uuid, rid, presupuesto, estado) VALUES (?, ?, ?, 'pendiente')");
            $stmt->bind_param("iid", $usuario_id, $rid, $precio);
            $stmt->execute();
        }

        $conexion->commit();
        header("Location: procesar_usuario.php");
        exit;
    } catch (Exception $e) {
        $conexion->rollback();
        echo "Error al procesar la reserva: " . $e->getMessage();
    }
} else {
    echo "No se ha seleccionado ningún recurso.";
}

$db->cerrar();
?>
