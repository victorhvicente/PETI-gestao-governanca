- Criação do banco de dados
CREATE DATABASE IF NOT EXISTS peti;
USE peti;
 
-- Tabela de objetivos
CREATE TABLE objetivo (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo ENUM('organizacional', 'ti') NOT NULL,
    descricao TEXT
) ENGINE=InnoDB;
 
-- Inserção de dados na tabela objetivo
INSERT INTO objetivo (tipo, descricao) VALUES
('organizacional', 'Descrição do objetivo organizacional'),
('ti', 'Descrição do objetivo de TI');
 
-- Tabela de projetos
CREATE TABLE projeto (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    objetivo_id INT NULL,
    responsavel VARCHAR(100) NOT NULL,
    custo DECIMAL(10,2) NOT NULL,
    prazo DATE NOT NULL,
    FOREIGN KEY (objetivo_id) REFERENCES objetivo(id) ON DELETE SET NULL
) ENGINE=InnoDB;
 
-- Inserção de dados na tabela projeto
INSERT INTO projeto (nome, objetivo_id, responsavel, custo, prazo) VALUES
('Projeto Exemplo', 1, 'Responsável Exemplo', 10000.00, '2025-12-31');
 
-- Tabela de missões vinculadas a projetos
CREATE TABLE missao_projeto (
    id INT AUTO_INCREMENT PRIMARY KEY,
    projeto_id INT NOT NULL,
    missao TEXT NOT NULL,
    FOREIGN KEY (projeto_id) REFERENCES projeto(id) ON DELETE CASCADE
) ENGINE=InnoDB;
 
-- Inserção de dados na tabela missao_projeto
INSERT INTO missao_projeto (projeto_id, missao) VALUES
(1, 'Missão do projeto Exemplo');
 
-- Tabela de visões vinculadas a projetos
CREATE TABLE visao_projeto (
    id INT AUTO_INCREMENT PRIMARY KEY,
    projeto_id INT NOT NULL,
    visao TEXT NOT NULL,
    FOREIGN KEY (projeto_id) REFERENCES projeto(id) ON DELETE CASCADE
) ENGINE=InnoDB;
 
-- Inserção de dados na tabela visao_projeto
INSERT INTO visao_projeto (projeto_id, visao) VALUES
(1, 'Visão do projeto Exemplo');
 
-- Tabela para relacionamento entre objetivos e projetos
CREATE TABLE objetivo_projeto (
    id INT AUTO_INCREMENT PRIMARY KEY,
    projeto_id INT,
    objetivo_id INT,
    descricao TEXT,
    FOREIGN KEY (projeto_id) REFERENCES projeto(id) ON DELETE CASCADE,
    FOREIGN KEY (objetivo_id) REFERENCES objetivo(id) ON DELETE CASCADE
) ENGINE=InnoDB;
 
-- Inserção de dados na tabela objetivo_projeto
INSERT INTO objetivo_projeto (projeto_id, objetivo_id, descricao) VALUES
(1, 1, 'Descrição do objetivo organizacional no projeto'),
(1, 2, 'Descrição do objetivo de TI no projeto');