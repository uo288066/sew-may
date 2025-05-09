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

// Verificar si se envió la solicitud de anulación
if (isset($_POST['anular']) && isset($_POST['reid'])) {
    $usuario_id = $_SESSION['usuario_id'];
    $reid = $_POST['reid'];

    // Verificar que la reserva pertenece al usuario
    $stmt = $conexion->prepare("SELECT r.estado FROM reserva r WHERE r.reid = ? AND r.uuid = ?");
    $stmt->bind_param("ii", $reid, $usuario_id);
    $stmt->execute();
    $stmt->bind_result($estado);
    $stmt->fetch();

    // Liberar el conjunto de resultados
    $stmt->free_result();

    if ($estado == 'pendiente' || $estado == 'confirmada') {
        // Actualizar el estado de la reserva a 'cancelada'
        $stmt = $conexion->prepare("UPDATE reserva SET estado = 'Cancelada' WHERE reid = ?");
        $stmt->bind_param("i", $reid);
        $stmt->execute();

        // Registrar la anulación en el historial
        $motivo = "Anulación solicitada por el usuario";
        $stmt = $conexion->prepare("INSERT INTO historial_anulaciones (reid, motivo) VALUES (?, ?)");
        $stmt->bind_param("is", $reid, $motivo);
        $stmt->execute();

        // Redirigir al usuario a su página de reservas
        header("Location: procesar_usuario.php");
        exit;  // Asegurarse de que el script se detenga aquí
    } else {
        echo "No se puede anular una reserva ya cancelada.";
    }
} else {
    echo "No se ha seleccionado una reserva válida.";
}

$db->cerrar();
?>
