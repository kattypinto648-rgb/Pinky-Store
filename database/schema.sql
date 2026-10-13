SET NAMES utf8mb4;
SET time_zone = '-03:00';

CREATE TABLE usuarios (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    nombre          VARCHAR(80)  NOT NULL,
    apellido        VARCHAR(80)  NOT NULL,
    email           VARCHAR(150) NOT NULL UNIQUE,
    clave_hash      VARCHAR(255) NOT NULL,
    telefono        VARCHAR(30)  DEFAULT NULL,
    direccion       VARCHAR(200) DEFAULT NULL,
    rol             ENUM('admin', 'cliente') NOT NULL DEFAULT 'cliente',
    activo          TINYINT(1)   NOT NULL DEFAULT 1,
    creado_en       DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE productos (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    codigo          VARCHAR(40)  NOT NULL UNIQUE,
    nombre          VARCHAR(150) NOT NULL,
    descripcion     TEXT         DEFAULT NULL,
    categoria       VARCHAR(80)  NOT NULL,
    color           VARCHAR(40)  DEFAULT NULL,
    precio          DECIMAL(10,2) NOT NULL,
    imagen          VARCHAR(255) NOT NULL,
    activo          TINYINT(1)   NOT NULL DEFAULT 1,
    creado_en       DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE variantes (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    id_producto     INT          NOT NULL,
    talle           VARCHAR(20)  NOT NULL,
    stock           INT          NOT NULL DEFAULT 0,
    FOREIGN KEY (id_producto) REFERENCES productos(id) ON DELETE CASCADE,
    UNIQUE KEY uk_prod_talle (id_producto, talle)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE clientes (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario      INT          NOT NULL,
    telefono        VARCHAR(30)  DEFAULT NULL,
    direccion       VARCHAR(200) DEFAULT NULL,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE pedidos (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    codigo          VARCHAR(30)  NOT NULL UNIQUE,
    id_usuario      INT          NOT NULL,
    fecha           DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    subtotal        DECIMAL(10,2) NOT NULL DEFAULT 0,
    costo_envio     DECIMAL(10,2) NOT NULL DEFAULT 0,
    total           DECIMAL(10,2) NOT NULL DEFAULT 0,
    metodo_pago     VARCHAR(40)  DEFAULT NULL,
    metodo_entrega  VARCHAR(40)  DEFAULT NULL,
    estado          ENUM('Pendiente','Enviado','Entregado','Cancelado')
                    NOT NULL DEFAULT 'Pendiente',
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE detalle_pedido (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    id_pedido       INT          NOT NULL,
    id_producto     INT          NOT NULL,
    talle           VARCHAR(20)  NOT NULL,
    cantidad        INT          NOT NULL,
    precio_unit     DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (id_pedido)   REFERENCES pedidos(id)   ON DELETE CASCADE,
    FOREIGN KEY (id_producto) REFERENCES productos(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE promociones (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    codigo          VARCHAR(30)  NOT NULL UNIQUE,
    nombre          VARCHAR(150) NOT NULL,
    porcentaje      INT          NOT NULL,
    activa          TINYINT(1)   NOT NULL DEFAULT 1,
    creado_en       DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE carrito (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario      INT          NOT NULL,
    creado_en       DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE detalle_carrito (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    id_carrito      INT          NOT NULL,
    id_producto     INT          NOT NULL,
    talle           VARCHAR(20)  NOT NULL,
    cantidad        INT          NOT NULL DEFAULT 1,
    precio_unit     DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (id_carrito)  REFERENCES carrito(id)   ON DELETE CASCADE,
    FOREIGN KEY (id_producto) REFERENCES productos(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;