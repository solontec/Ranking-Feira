-- Cria o banco de dados
CREATE DATABASE IF NOT EXISTS Ranking;
USE Ranking;

-- Cria a tabela 'votos' com restrição para que o campo 'voto' aceite apenas valores de 1 a 5
CREATE TABLE IF NOT EXISTS votos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    voto INT NOT NULL CHECK (voto BETWEEN 1 AND 5)
);

INSERT INTO votos (nome, voto) VALUES
-- Projeto A
('Projeto A', 5),
('Projeto A', 4),
('Projeto A', 3),
('Projeto A', 5),

-- Projeto B
('Projeto B', 2),
('Projeto B', 3),
('Projeto B', 4),

-- Projeto C
('Projeto C', 5),
('Projeto C', 5),
('Projeto C', 5),
('Projeto C', 4),

-- Projeto D
('Projeto D', 1),
('Projeto D', 2),
('Projeto D', 2),

-- Projeto E
('Projeto E', 3),
('Projeto E', 4),
('Projeto E', 5),
('Projeto E', 5),
('Projeto E', 4);