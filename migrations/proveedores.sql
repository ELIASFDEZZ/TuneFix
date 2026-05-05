-- ============================================================
-- MIGRACIÓN: Sistema de Proveedores TuneFix
-- Ejecutar en la base de datos: tunefix
-- ============================================================

-- Tabla principal de proveedores (solicitudes + cuentas aceptadas)
CREATE TABLE IF NOT EXISTS proveedores (
  id                 INT          PRIMARY KEY AUTO_INCREMENT,
  nombre_empresa     VARCHAR(255) NOT NULL,
  cif                VARCHAR(20)  NOT NULL UNIQUE,
  direccion          TEXT         NOT NULL,
  provincia          VARCHAR(100),
  telefono           VARCHAR(20),
  sitio_web          VARCHAR(255),
  nombre_responsable VARCHAR(255),
  email              VARCHAR(255) NOT NULL UNIQUE,
  password           VARCHAR(255) NOT NULL,
  descripcion        TEXT,
  doc_cif            VARCHAR(255),
  doc_iae            VARCHAR(255),
  estado             ENUM('pendiente','aceptado','rechazado') DEFAULT 'pendiente',
  motivo_rechazo     TEXT,
  created_at         TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
  updated_at         TIMESTAMP    DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Piezas publicadas por proveedores
CREATE TABLE IF NOT EXISTS piezas_proveedor (
  id             INT            PRIMARY KEY AUTO_INCREMENT,
  proveedor_id   INT            NOT NULL,
  nombre         VARCHAR(255)   NOT NULL,
  referencia_oem VARCHAR(100),
  categoria      VARCHAR(100),
  estado_pieza   ENUM('nueva','usada_buena','usada_desgaste') DEFAULT 'nueva',
  precio         DECIMAL(10,2)  NOT NULL,
  stock          INT            DEFAULT 0,
  descripcion    TEXT,
  garantia       VARCHAR(50),
  activa         TINYINT(1)     DEFAULT 1,
  created_at     TIMESTAMP      DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (proveedor_id) REFERENCES proveedores(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Fotos de cada pieza
CREATE TABLE IF NOT EXISTS piezas_proveedor_fotos (
  id       INT          PRIMARY KEY AUTO_INCREMENT,
  pieza_id INT          NOT NULL,
  ruta     VARCHAR(255) NOT NULL,
  orden    INT          DEFAULT 0,
  FOREIGN KEY (pieza_id) REFERENCES piezas_proveedor(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Vehículos compatibles con cada pieza
CREATE TABLE IF NOT EXISTS piezas_proveedor_vehiculos (
  id         INT          PRIMARY KEY AUTO_INCREMENT,
  pieza_id   INT          NOT NULL,
  marca      VARCHAR(100) NOT NULL,
  modelo     VARCHAR(100) NOT NULL,
  anio_desde INT,
  anio_hasta INT,
  motor      VARCHAR(100),
  FOREIGN KEY (pieza_id) REFERENCES piezas_proveedor(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
