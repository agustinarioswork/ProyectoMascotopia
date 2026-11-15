CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    rol ENUM('admin', 'cliente') DEFAULT 'cliente',
    activo TINYINT(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE productos (
    id_producto INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    especie ENUM('Perro', 'Gato', 'Peces', 'Aves', 'Conejos') NOT NULL,
    categoria ENUM('Alimentos', 'Accesorios', 'Juguetes', 'Jaulas') NOT NULL,
    activo TINYINT(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO productos (nombre, precio, stock, especie, categoria) VALUES
('Alimento Pro Plan Perro 15kg', 45000.00, 12, 'Perro', 'Alimentos'),
('Correa Retráctil 5m', 15000.00, 8, 'Perro', 'Accesorios'),
('Pelota de Goma Maciza', 6500.00, 20, 'Perro', 'Juguetes'),
('Rascador Torre para Gatos', 22000.00, 0, 'Gato', 'Juguetes'),
('Alimento Royal Canin Gato 7.5kg', 38000.00, 10, 'Gato', 'Alimentos'),
('Terrario / Jaula Grande Conejos', 85000.00, 4, 'Conejos', 'Jaulas'),
('Alimento en Escamas Peces Tropicales', 8200.00, 15, 'Peces', 'Alimentos'),
('Columpio de Madera para Aves', 5400.00, 9, 'Aves', 'Juguetes');

CREATE TABLE pedidos (
    id_pedido INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    fecha_pedido DATETIME DEFAULT CURRENT_TIMESTAMP,
    total DECIMAL(10,2) NOT NULL,
    estado ENUM('Pendiente', 'Confirmado', 'Entregado') DEFAULT 'Confirmado',
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE detalle_pedidos (
    id_detalle INT AUTO_INCREMENT PRIMARY KEY,
    id_pedido INT NOT NULL,
    id_producto INT NOT NULL,
    cantidad INT NOT NULL,
    precio_unitario DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (id_pedido) REFERENCES pedidos(id_pedido) ON DELETE CASCADE,
    FOREIGN KEY (id_producto) REFERENCES productos(id_producto)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE productos ADD COLUMN imagen VARCHAR(150) DEFAULT 'default.jpg' AFTER categoria;

UPDATE productos SET imagen = 'prod-proplan-perro.jpg' WHERE id_producto = 1;
UPDATE productos SET imagen = 'prod-correa-perro.jpg' WHERE id_producto = 2;
UPDATE productos SET imagen = 'prod-pelota-perro.jpg' WHERE id_producto = 3;
UPDATE productos SET imagen = 'prod-rascador-gato.jpg' WHERE id_producto = 4;
UPDATE productos SET imagen = 'prod-royal-gato.jpg' WHERE id_producto = 5;
UPDATE productos SET imagen = 'prod-jaula-conejo.jpg' WHERE id_producto = 6;
UPDATE productos SET imagen = 'prod-escamas-peces.jpg' WHERE id_producto = 7;
UPDATE productos SET imagen = 'prod-columpio-aves.jpg' WHERE id_producto = 8;


INSERT INTO productos (nombre, precio, stock, especie, categoria, imagen) VALUES
('Cama Acolchada Impermeable Perro', 32000.00, 6, 'Perro', 'Accesorios', 'prod-cama-perro.jpg'),
('Arenero Cerrado con Filtro Gato', 26500.00, 9, 'Gato', 'Accesorios', 'prod-arenero-gato.jpg'),
('Ratón de Felpa con Catnip', 4500.00, 18, 'Gato', 'Juguetes', 'prod-raton-gato.jpg'),
('Acuario de Vidrio 40 Litros', 58000.00, 3, 'Peces', 'Jaulas', 'prod-acuario-peces.jpg'),
('Jaula Amplia para Aves con Comederos', 42000.00, 5, 'Aves', 'Jaulas', 'prod-jaula-aves.jpg'),
('Heno Timothy Natural Conejos 1kg', 11000.00, 14, 'Conejos', 'Alimentos', 'prod-heno-conejo.jpg');

