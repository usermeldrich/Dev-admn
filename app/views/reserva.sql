
-- Criação da tabela de usuários
CREATE TABLE usuarios (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    usuario VARCHAR(50) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Criação da tabela de veículos
CREATE TABLE veiculos (
    id SERIAL PRIMARY KEY,
    modelo VARCHAR(100) NOT NULL,
    placa VARCHAR(20) UNIQUE NOT NULL,
    status VARCHAR(20) DEFAULT 'disponível',
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Criação da tabela de solicitações
CREATE TABLE solicitacoes (
    id SERIAL PRIMARY KEY,
    usuario_id INT REFERENCES usuarios(id) ON DELETE CASCADE,
    veiculo_id INT REFERENCES veiculos(id) ON DELETE CASCADE,
    data_solicitacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    data_devolucao TIMESTAMP,
    status VARCHAR(20) DEFAULT 'pendente'
);

-- Inserção dos carros
INSERT INTO veiculos (modelo, placa) VALUES 
('FIAT UNO ADESIVADO', 'QXI-2G02'),
('FIAT UNO ADESIVADO', 'QXK-1C37'),
('FIAT UNO QUADRADO', 'PEG-804I'),
('FIAT UNO QUADRADO', 'NMM-6233'),
('FIAT STRADA C/ BAÚ', 'QWZ-6E78'),
('FIAT STRADA C/ BAÚ', 'QWZ-6490'),
('FIAT STRADA S/ BAÚ', 'QYE-1G28'),
('FIAT STRADA S/ BAÚ', 'QYE-096I');

-- Inserção das motos
INSERT INTO veiculos (modelo, placa) VALUES 
('MOTO CG150 BAÚ', 'PDP-0J82'),
('MOTO CG150 BAÚ', 'PDP-1B02');
