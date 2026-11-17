DROP DATABASE IF EXISTS restaurante;
CREATE DATABASE restaurante CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE restaurante;

-- 1. ROLES
CREATE TABLE roles (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(30) NOT NULL UNIQUE,
    descripcion VARCHAR(150),
    activo TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB;

-- 2. USUARIOS
CREATE TABLE usuarios (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_rol INT UNSIGNED NOT NULL,
    nombre VARCHAR(60) NOT NULL,
    apellido VARCHAR(60) NOT NULL,
    username VARCHAR(40) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    fecha_creacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    deleted_at DATETIME DEFAULT NULL,
    CONSTRAINT fk_usuarios_roles FOREIGN KEY (id_rol) REFERENCES roles(id) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

-- 3. MESAS
CREATE TABLE mesas (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    numero INT UNSIGNED NOT NULL UNIQUE,
    capacidad TINYINT UNSIGNED NOT NULL,
    fecha_creacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion DATETIME DEFAULT NULL,
    deleted_at DATETIME DEFAULT NULL
) ENGINE=InnoDB;

-- 4. CATEGORIAS
CREATE TABLE categorias (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    descripcion VARCHAR(150),
    fecha_creacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    deleted_at DATETIME DEFAULT NULL
) ENGINE=InnoDB;

-- 5. PRODUCTOS
CREATE TABLE productos (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_categoria INT UNSIGNED NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    descripcion VARCHAR(255),
    precio DECIMAL(10,2) NOT NULL,
    disponible TINYINT(1) NOT NULL DEFAULT 1,
    fecha_creacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    deleted_at DATETIME DEFAULT NULL,
    CONSTRAINT chk_productos_precio CHECK (precio >= 0),
    CONSTRAINT fk_productos_categorias FOREIGN KEY (id_categoria) REFERENCES categorias(id) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

-- 6. PEDIDOS
CREATE TABLE pedidos (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_mesa INT UNSIGNED NOT NULL,
    id_usuario INT UNSIGNED NOT NULL,
    estado ENUM('abierto', 'cerrado', 'cancelado') NOT NULL DEFAULT 'abierto',
    fecha_apertura DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    fecha_cierre DATETIME DEFAULT NULL,
    CONSTRAINT fk_pedidos_mesas FOREIGN KEY (id_mesa) REFERENCES mesas(id) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_pedidos_usuarios FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

-- 7. COMANDAS
CREATE TABLE comandas (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_pedido INT UNSIGNED NOT NULL,
    id_usuario INT UNSIGNED NOT NULL,
    estado ENUM('pendiente', 'en_preparacion', 'preparada', 'entregada', 'cancelada') NOT NULL DEFAULT 'pendiente',
    fecha_creacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_comandas_pedidos FOREIGN KEY (id_pedido) REFERENCES pedidos(id) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_comandas_usuarios FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

-- 8. DETALLE_COMANDA
CREATE TABLE detalle_comanda (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_comanda INT UNSIGNED NOT NULL,
    id_producto INT UNSIGNED NOT NULL,
    nombre_producto VARCHAR(100) NOT NULL,
    precio_unitario DECIMAL(10,2) NOT NULL,
    cantidad INT UNSIGNED NOT NULL,
    observaciones VARCHAR(255) DEFAULT NULL,
    CONSTRAINT chk_detalle_precio CHECK (precio_unitario >= 0),
    CONSTRAINT chk_detalle_cantidad CHECK (cantidad > 0),
    CONSTRAINT fk_detalle_comandas FOREIGN KEY (id_comanda) REFERENCES comandas(id) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_detalle_productos FOREIGN KEY (id_producto) REFERENCES productos(id) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

-- 9. HISTORIAL_COMANDAS
CREATE TABLE historial_comandas (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_comanda INT UNSIGNED NOT NULL,
    id_usuario INT UNSIGNED NOT NULL,
    estado_anterior ENUM('pendiente', 'en_preparacion', 'preparada', 'entregada', 'cancelada') DEFAULT NULL,
    estado_nuevo ENUM('pendiente', 'en_preparacion', 'preparada', 'entregada', 'cancelada') NOT NULL,
    observacion VARCHAR(255) DEFAULT NULL,
    fecha_hora DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_historial_comandas FOREIGN KEY (id_comanda) REFERENCES comandas(id) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_historial_usuarios FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

-- 10. AUDITORIA
CREATE TABLE auditoria (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT UNSIGNED NOT NULL,
    entidad VARCHAR(50) NOT NULL,
    id_registro INT UNSIGNED NOT NULL,
    accion ENUM('INSERT', 'UPDATE', 'BAJA') NOT NULL,
    datos_anteriores JSON DEFAULT NULL,
    datos_nuevos JSON DEFAULT NULL,
    fecha_hora DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_auditoria_usuarios FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

-- DATOS INICIALES (SEEDS)
INSERT INTO roles (id, nombre, descripcion, activo) VALUES
(1, 'Administrador', 'Gestiona usuarios, mesas, categorias y productos', 1),
(2, 'Mozo', 'Crea pedidos, envia comandas y registra entregas', 1),
(3, 'Cocina', 'Visualiza comandas y actualiza su estado', 1);

INSERT INTO usuarios (id, id_rol, nombre, apellido, username, password_hash) VALUES
(1, 1, 'Admin', 'Sistema', 'admin', '$2y$10$Sa.vGHVcuPAebtKd.2totufDuVW3lxE6mz8I/UxvhB4JZEE.iM6ju');
