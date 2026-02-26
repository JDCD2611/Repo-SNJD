CREATE TABLE IF NOT EXISTS tickets (
    id SERIAL PRIMARY KEY,
    usuario VARCHAR(100) NOT NULL,
    descripcion_falla TEXT NOT NULL,
    fecha_reporte TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    resuelto BOOLEAN DEFAULT FALSE
);

INSERT INTO tickets (usuario, descripcion_falla) VALUES ('Usuario Inicial', 'Falla de prueba');