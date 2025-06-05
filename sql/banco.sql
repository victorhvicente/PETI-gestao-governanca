CREATE DATABASE peti;

USE peti;

CREATE TABLE organizacao (
    id INT AUTO_INCREMENT PRIMARY KEY,
    missao TEXT,
    visao TEXT
);

CREATE TABLE objetivo (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo ENUM('organizacional', 'ti'),
    descricao TEXT
);

CREATE TABLE projeto (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100),
    objetivo_id INT,
    responsavl VARCHAR(100),
    custo DECIMAL(10,2),
    prazo DATE,
    FOREIGN KEY (objetivo_id) REFERENCES objetivo(id)
);
