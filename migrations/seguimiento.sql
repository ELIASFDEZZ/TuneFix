-- Migración: Sistema de seguimiento de profesionales
-- Ejecutar una sola vez sobre la base de datos 'tunefix'

-- 1. Añadir columna autor a la tabla tutorial
ALTER TABLE tutorial ADD COLUMN usuario_id INT NULL AFTER id;
ALTER TABLE tutorial ADD CONSTRAINT fk_tutorial_usuario FOREIGN KEY (usuario_id) REFERENCES usuario(id) ON DELETE SET NULL;

-- 2. Tabla de seguimiento entre usuarios
CREATE TABLE IF NOT EXISTS seguimiento (
  id                INT AUTO_INCREMENT PRIMARY KEY,
  seguidor_id       INT NOT NULL,
  profesional_id    INT NOT NULL,
  fecha_seguimiento DATETIME DEFAULT NOW(),
  UNIQUE KEY uq_seguimiento (seguidor_id, profesional_id),
  CONSTRAINT fk_seg_seguidor    FOREIGN KEY (seguidor_id)    REFERENCES usuario(id) ON DELETE CASCADE,
  CONSTRAINT fk_seg_profesional FOREIGN KEY (profesional_id) REFERENCES usuario(id) ON DELETE CASCADE
);
