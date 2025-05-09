<?php
class Usuario {
    private $db;
    private $id;
    private $nombre;
    private $correo;
    private $contrasena;
    private $fecha_registro;

    public function __construct($db) {
        $this->db = $db;
    }

    public function registrar($nombre, $correo, $contrasena) {
        $sql = "INSERT INTO usuario (nombre, correo, contrasena, fecha_registro) 
                VALUES (?, ?, ?, NOW())";
        $stmt = $this->db->prepare($sql);
        $hash = password_hash($contrasena, PASSWORD_DEFAULT); // ✅ primero en variable
        $stmt->bind_param("sss", $nombre, $correo, $hash);    // ✅ luego la variable
        return $stmt->execute();
    }


    public function autenticar($correo, $contrasena) {
        $sql = "SELECT uuid, contrasena FROM usuario WHERE correo = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows == 1) {
            $stmt->bind_result($id, $hashedPassword);
            $stmt->fetch();
            if (password_verify($contrasena, $hashedPassword)) {
                $this->id = $id;
                return true;
            }
        }
        return false;
    }

    public function getId() {
        return $this->id;
    }
}
?>
