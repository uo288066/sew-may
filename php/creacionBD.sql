CREATE TABLE usuario (
    uuid INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    correo VARCHAR(100) UNIQUE NOT NULL,
    contrasena VARCHAR(255) NOT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE categoria (
    cid INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL
);

CREATE TABLE recurso (
    rid INT AUTO_INCREMENT PRIMARY KEY,
    cid INT,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10, 2) NOT NULL,
    plazas INT NOT NULL,
    fecha_inicio DATETIME NOT NULL,
    fecha_fin DATETIME NOT NULL,
    estado ENUM('disponible', 'reservado') DEFAULT 'disponible' NOT NULL,
    FOREIGN KEY (cid) REFERENCES categoria(cid)
);

CREATE TABLE reserva (
    reid INT AUTO_INCREMENT PRIMARY KEY,
    uuid INT,
    rid INT,
    fecha_reserva TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    presupuesto DECIMAL(10, 2) NOT NULL,
    estado ENUM('pendiente', 'confirmada', 'cancelada') DEFAULT 'pendiente',
    FOREIGN KEY (uuid) REFERENCES usuario(uuid),
    FOREIGN KEY (rid) REFERENCES recurso(rid)
);

CREATE TABLE historial_anulaciones (
    aid INT AUTO_INCREMENT PRIMARY KEY,
    reid INT,
    fecha_anulacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    motivo TEXT,
    FOREIGN KEY (reid) REFERENCES reserva(reid)
);



