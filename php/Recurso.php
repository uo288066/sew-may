<?php
class Recurso {
    private $db;
    private $id;
    private $nombre;
    private $descripcion;
    private $precio;
    private $plazas;
    private $fecha_inicio;
    private $fecha_fin;

    public function __construct($db) {
        $this->db = $db;
    }

    public function listar() {
        $sql = "SELECT * FROM recurso";
        $result = $this->db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function obtenerPorId($id) {
        $sql = "SELECT * FROM recurso WHERE rid = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}
?>
