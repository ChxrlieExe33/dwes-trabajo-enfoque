CREATE DATABASE IF NOT EXISTS dwes
    DEFAULT CHARACTER SET utf8mb4
    DEFAULT COLLATE utf8mb4_unicode_ci;

USE dwes;

CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellidos VARCHAR(200) NOT NULL,
    email VARCHAR(200) NOT NULL,
    password VARCHAR(100) NOT NULL,
    fecha_nac DATE,
    es_admin BOOLEAN NOT NULL,
    direccion_entrega VARCHAR(150),
    ciudad_entrega VARCHAR(50),
    provincia_entrega VARCHAR(25),
    direccion_facturacion VARCHAR(150),
    ciudad_facturacion VARCHAR(50),
    provincia_facturacion VARCHAR(25)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE productos (
    id_producto INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(200) NOT NULL,
    descripcion VARCHAR(200) NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    color VARCHAR(50) NOT NULL,
    nombre_fabricante VARCHAR(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE multimedia_productos (
    id_multimedia INT AUTO_INCREMENT PRIMARY KEY,
    id_producto INT NOT NULL,
    fichero VARCHAR(100) NOT NULL,
    CONSTRAINT FK_multimedia_productos FOREIGN KEY (id_producto)
        REFERENCES productos(id_producto)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE disponibilidad_productos (
    id_disponibilidad INT AUTO_INCREMENT PRIMARY KEY,
    id_producto INT NOT NULL,
    talla INT NOT NULL,
    cantidad INT NOT NULL DEFAULT 0,
    CONSTRAINT FK_disponibilidad_productos FOREIGN KEY (id_producto)
        REFERENCES productos(id_producto)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE carritos (
    id_carrito INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    importe DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    CONSTRAINT FK_carritos_usuarios FOREIGN KEY (id_usuario)
        REFERENCES usuarios(id_usuario)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE productos_carritos (
    id_producto_carrito INT AUTO_INCREMENT PRIMARY KEY,
    id_producto INT NOT NULL,
    id_carrito INT NOT NULL,
    tamano INT NOT NULL,
    cantidad INT NOT NULL,
    CONSTRAINT FK_productos_carrito FOREIGN KEY (id_carrito)
        REFERENCES carritos(id_carrito)
        ON DELETE CASCADE,
    CONSTRAINT FK_productos_carrito_producto FOREIGN KEY (id_producto)
        REFERENCES productos(id_producto)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE compras (
    id_compra INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    fecha DATE NOT NULL,
    direccion_entrega VARCHAR(150) NOT NULL,
    ciudad_entrega VARCHAR(50) NOT NULL,
    provincia_entrega VARCHAR(25) NOT NULL,
    direccion_facturacion VARCHAR(150) NOT NULL,
    ciudad_facturacion VARCHAR(50) NOT NULL,
    provincia_facturacion VARCHAR(25) NOT NULL,
    CONSTRAINT FK_compras_usuarios FOREIGN KEY (id_usuario)
        REFERENCES usuarios(id_usuario)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE productos_compras (
    id_producto_compra INT AUTO_INCREMENT PRIMARY KEY,
    id_producto INT NOT NULL,
    id_compra INT NOT NULL,
    tamano INT NOT NULL,
    cantidad INT NOT NULL,
    CONSTRAINT FK_productos_compra FOREIGN KEY (id_compra)
        REFERENCES compras(id_compra)
        ON DELETE CASCADE,
    CONSTRAINT FK_productos_compra_producto FOREIGN KEY (id_producto)
        REFERENCES productos(id_producto)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO usuarios (nombre, apellidos, email, password, fecha_nac, es_admin, direccion_entrega, ciudad_entrega, provincia_entrega, direccion_facturacion, ciudad_facturacion, provincia_facturacion)
    VALUES ('Charlie', 'Crane', 'charlie@test.com', '$2y$10$CkRpFBOffPJ7M2U76w3IfuIQXqLI7Di7IIkAVn.CDEdOLBO5Kusp6', '2003-06-13', 1,
    'Prueba dirección', 'Mijas', 'Málaga', 'Prueba dirección', 'Mijas', 'Málaga');

INSERT INTO productos (`id_producto`, `nombre`, `descripcion`, `precio`, `color`, `nombre_fabricante`) VALUES
     (1, 'Jordan 1s', 'Zapatos de baloncesto jordan 1.', 120.00, 'Blanco y negro', 'Nike'),
     (2, 'Panda dunks', 'Panda dunk zapatos', 100.00, 'Blanco y negro', 'Nike'),
     (3, 'Airmax 95s', 'Zapatos airmax', 180.00, 'Gris', 'Nike');

INSERT INTO multimedia_productos (`id_multimedia`, `id_producto`, `fichero`) VALUES
       (1, 1, 'jordans.webp'),
       (2, 2, 'dunks.webp'),
       (3, 3, 'airmax95.png'),
       (4, 1, 'jordans2.webp');

INSERT INTO `disponibilidad_productos` (`id_disponibilidad`, `id_producto`, `talla`, `cantidad`) VALUES
(1, 1, 45, 5),
(2, 1, 42, 3),
(3, 1, 41, 1),
(4, 2, 46, 2),
(5, 2, 40, 3),
(6, 3, 43, 4);