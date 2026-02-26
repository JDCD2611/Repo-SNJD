CREATE TABLE IF NOT EXISTS sugerencias (
    id SERIAL PRIMARY KEY,
    tema VARCHAR(100) NOT NULL,
    detalle_sugerencia TEXT NOT NULL,
    fecha_envio TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    revisada BOOLEAN DEFAULT FALSE
);

INSERT INTO tickets (usuario, descripcion_falla) VALUES ('general', 'sugerencias');