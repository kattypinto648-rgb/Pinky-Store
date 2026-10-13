USE pinky_store;

DELETE FROM detalle_pedido;
DELETE FROM pedidos;
DELETE FROM detalle_carrito;
DELETE FROM carrito;
DELETE FROM variantes;
DELETE FROM productos;
DELETE FROM clientes;
DELETE FROM usuarios;
DELETE FROM promociones;

INSERT INTO usuarios (nombre, apellido, email, clave_hash, telefono, direccion, rol, activo) VALUES
('Admin',    'Pinky',   'administracionpinky@gmail.com',    '$2y$10$QsWrA2mFSGjt0exzZ6eqYuoWbcc9iKsc4X3GAtVhsQdpQ3L1gBdA.', '11-2461-4235', 'Av. San Martín 4717, CABA', 'admin',   1),
('Katty',    'Pinto',   'cliente1@gmail.com', '$2y$10$t/yPjd3NTdzE1YKREkBlI.CV/aebUKJfGyE0K.m/UjCdNpNVvO4U6', '11-1234-2231', 'Av. Santa Fe 2345, CABA',   'cliente', 1),
('Emmanuel',    'enet',   'cliente2@gmail.com', '$2y$10$t/yPjd3NTdzE1YKREkBlI.CV/aebUKJfGyE0K.m/UjCdNpNVvO4U6', '11-4123-1234', 'Av. Carabobo 567, CABA',      'cliente', 1);

INSERT INTO productos (codigo, nombre, descripcion, categoria, color, precio, imagen) VALUES
('CAM-01', 'Camisa Oxford ',    'Camisa de algodón, corte clásico',  'Camisas',      'Blanco', 100000.00, '/img/productos/camisa1.jpeg'),
('CAM-02', 'Camisa de Seda ',     'Camisa de seda con caída suave',    'Camisas',      'Rosa',    90999.00, '/img/productos/camisa2.jpeg'),
('CMP-01', 'Campera de Jean ',   'Campera de jean oversize',          'Camperas',     'Negro',  150000.00, '/img/productos/campera1.jpeg'),
('CMP-02', 'Campera de Paño ',   'Campera abrigada para invierno',    'Camperas',     'Beige',  160000.00, '/img/productos/campera2.jpeg'),
('CAR-01', 'Cartera Tote de ',   'Cartera amplia, cuero ecológico',   'Carteras',     'Negro',  100000.00, '/img/productos/cartera1.jpeg'),
('CAR-02', 'Cartera Bandolera ', 'Bandolera con cadena dorada',       'Carteras',     'Beige',   90000.00, '/img/productos/cartera2.jpeg'),
('CAR-03', 'Cartera Mini ',       'Mini cartera para salidas',         'Carteras',     'Rosa',    99000.00, '/img/productos/cartera3.jpeg'),
('CAR-04', 'Cartera Crossbody ', 'Crossbody con correa ajustable',    'Carteras',     'Negro',  102000.00, '/img/productos/cartera4.jpeg'),
('CAR-05', 'Cartera Shopper ',    'Shopper amplia de cuero suave',     'Carteras',     'Rosa',   133000.00, '/img/productos/cartera5.jpeg'),
('CAR-06', 'Cartera Clasica ',   'Cartera mediana con cierre',        'Carteras',     'Beige',   70000.00, '/img/productos/cartera6.jpeg'),
('CAR-07', 'Cartera Pequena ',   'Ideal para salidas nocturnas',      'Carteras',     'Negro',   65000.00, '/img/productos/cartera7.jpeg'),
('CAR-08', 'Cartera Hobo ',      'Cuero sintetico, caida suave',      'Carteras',     'Camel',   98000.00, '/img/productos/cartera8.jpeg'),
('CAR-10', 'Cartera Tote ',      'Tote grande para el dia a dia',     'Carteras',     'Beige',   79000.00, '/img/productos/cartera10.jpeg'),
('CAR-11', 'Cartera Mochila ',   'Mochila de cuero casual',           'Carteras',     'Negro',   89000.00, '/img/productos/cartera11.jpeg'),
('CAR-12', 'Cartera Bandolera Camel', 'Bandolera clasica con solapa',      'Carteras',     'Camel',  100900.00, '/img/productos/cartera12.jpeg'),
('CAR-13', 'Cartera Mini ',       'Mini cartera para eventos',         'Carteras',     'Rosa',    45000.00, '/img/productos/cartera13.jpeg'),
('CHO-01', 'Chomba Piqué ',       'Chomba con cuello camisero',        'Chombas',      'Rosa',    32000.00, '/img/productos/chomba1.jpeg'),
('MGL-01', 'Manga Larga ',      'Básica de algodón, cuello redondo', 'Mangas largas','Blanco',  34000.00, '/img/productos/mangalarga1.jpeg'),
('REM-01', 'Remera Básica ',    'Remera de algodón peinado',         'Remeras',      'Blanco',  34000.00, '/img/productos/remera1.jpeg'),
('REM-02', 'Remera Básica ',     'Remera de algodón peinado',         'Remeras',      'Negro',   20000.00, '/img/productos/remera2.jpeg'),
('REM-03', 'Remera Básica Rsa',      'Remera de algodón peinado',         'Remeras',      'Rosa',    20000.00, '/img/productos/remera3.jpeg');

INSERT INTO variantes (id_producto, talle, stock) VALUES
(1, 'XS', 1), (1, 'S', 2), (1, 'M', 3), (1, 'L', 2), (1, 'XL', 1),
(2, 'XS', 1), (2, 'S', 2), (2, 'M', 3),
(3, 'XS', 1), (3, 'S', 2), (3, 'M', 3), (3, 'L', 1),
(4, 'S', 2), (4, 'M', 2), (4, 'L', 2),
(5, 'Unico', 5), (6, 'Unico', 3), (7, 'Unico', 4), (8, 'Unico', 3),
(9, 'Unico', 2), (10, 'Unico', 4), (11, 'Unico', 3), (12, 'Unico', 2),
(13, 'Unico', 5), (14, 'Unico', 4), (15, 'Unico', 3), (16, 'Unico', 2),
(17, 'Unico', 3),
(18, 'XS', 1), (18, 'S', 2), (18, 'M', 3),
(19, 'XS', 1), (19, 'S', 2), (19, 'M', 3),
(20, 'XS', 1), (20, 'S', 2), (20, 'M', 3), (20, 'L', 2),
(21, 'XS', 1), (21, 'S', 2), (21, 'M', 3),
(22, 'XS', 1), (22, 'S', 2), (22, 'M', 3);

INSERT INTO promociones (codigo, nombre, porcentaje, activa) VALUES
('PROMO01', 'Descuento Verano', 20, 1),
('PROMO02', '2x1 en Remeras',   50, 1),
('PROMO03', 'Black Friday',     30, 0);