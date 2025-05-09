<?php
include_once "DB.php";
include_once "reserva.php";

session_start();

// Comprobar que el usuario está autenticado
if (!isset($_SESSION['usuario_id'])) {
    die("Debes estar autenticado para realizar una reserva.");
}

$usuario_id = $_SESSION['usuario_id'];
$recurso_id = $_POST['recurso'];
$presupuesto = $_POST['presupuesto'];

$db = new DB();
$conexion = $db->getConexion();
$reserva = new Reserva($conexion);

if ($reserva->crear($usuario_id, $recurso_id, $presupuesto)) {
    echo "Reserva realizada con éxito!";
} else {
    echo "Hubo un error al realizar la reserva.";
}

$db->cerrar();
?>
