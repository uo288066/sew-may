<?php
include_once "DB.php";
include_once "Usuario.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $contrasena = $_POST['contrasena'];

    // Conectar a la base de datos
    $db = new DB();
    $conexion = $db->getConexion();
    $usuario = new Usuario($conexion);

    // Verificar si el correo ya está registrado
    $stmt = $conexion->prepare("SELECT uuid FROM usuario WHERE correo = ?");
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        echo "El correo ya está registrado. <a href='../login.html'>Iniciar sesión</a>";
    } else {
        // Registrar el nuevo usuario
        if ($usuario->registrar($nombre, $correo, $contrasena)) {
            echo "¡Registro exitoso! <a href='../login.html'>Iniciar sesión</a>";
        } else {
            echo "Hubo un error en el registro. Intenta nuevamente.";
        }
    }

    $db->cerrar();
}
?>
