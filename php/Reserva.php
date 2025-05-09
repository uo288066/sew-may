<?php
class Reserva {
    private $db;
    private $id;
    private $id_usuario;
    private $id_recurso;
    private $fecha_reserva;
    private $presupuesto;
    private $estado;

    public function __construct($db) {
        $this->db = $db;
    }

    public function hacerReserva($usuario_id, $recurso_id, $presupuesto) {
        $query = "INSERT INTO reserva (uuid, rid, presupuesto) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("iid", $usuario_id, $recurso_id, $presupuesto);
        return $stmt->execute();
    }

    public function obtenerReservasPorUsuario($id_usuario) {
        $sql = "SELECT * FROM reservas WHERE id_usuario = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function anularReserva($id_reserva) {
        $sql = "UPDATE reservas SET estado = 'Anulada' WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id_reserva);
        return $stmt->execute();
    }
}
?>
