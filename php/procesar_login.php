<?php
session_start();
include_once "DB.php";
include_once "Usuario.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = $_POST['correo'];
    $contrasena = $_POST['contrasena'];

    // Conectar a la base de datos
    $db = new DB();
    $conexion = $db->getConexion();
    $usuario = new Usuario($conexion);

    // Verificar las credenciales del usuario
    if ($usuario->autenticar($correo, $contrasena)) {
        $_SESSION['usuario_id'] = $usuario->getId(); // Guardar el ID del usuario en la sesión
        header("Location: reservas.php");
        exit();

    } else {
        echo "Correo o contraseña incorrectos. <a href='../login.html'>Intenta nuevamente</a>";
    }

    $db->cerrar();
}
?>
