<?php
class DB {
    private $host = "localhost";
    private $usuario = "DBUSER2025";
    private $contrasena = "DBPWD2025";
    private $baseDeDatos = "sew25";
    private $conexion;

    public function __construct() {
        $this->conexion = new mysqli($this->host, $this->usuario, $this->contrasena, $this->baseDeDatos);
        if ($this->conexion->connect_error) {
            die("Conexión fallida: " . $this->conexion->connect_error);
        }
    }

    public function getConexion() {
        return $this->conexion;
    }

    public function cerrar() {
        $this->conexion->close();
    }
}
?>
