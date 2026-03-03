CREATE TABLE IF NOT EXISTS sugerencias (
    id SERIAL PRIMARY KEY,
    tema VARCHAR(100),
    detalle_sugerencia TEXT,
    revisada BOOLEAN DEFAULT FALSE
);

CREATE TABLE IF NOT EXISTS alumnos (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(100),
    rol VARCHAR(100),
    bio TEXT,
    habilidades VARCHAR(255),
    foto VARCHAR(255)
);

INSERT INTO alumnos (nombre, rol, bio, habilidades, foto) VALUES 
('Jorge Chirinos', 'Desarrollador del Backend, especialista de Python', 'Estudiante de ingeniería en computación, desarrollador principal del Backend, Titulo en CCNA introducción a redes by CISCO.', 'PHP, Docker, Git, C++, Python, SQL.', 'jorge.jpeg'),
('Sebastián Naranjo', 'Programador de Paginas Web', 'Estudiante de ingeniería en computación, desarrollador enfocado en Frontend, gran colaborador, programador Junior.', 'PHP, HTML.', 'sebastian.jpeg');

INSERT INTO sugerencias (tema, detalle_sugerencia) VALUES ('General', 'Sugerencia inicial de prueba');