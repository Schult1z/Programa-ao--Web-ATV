-- Criação da tabela de funcionários
CREATE TABLE funcionarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  cpf VARCHAR(14) NOT NULL,
  data_nascimento VARCHAR(10) NOT NULL,
  salario DECIMAL(10, 2) NOT NULL
);

-- Inserção de dados de exemplo
INSERT INTO funcionarios (nome, cpf, data_nascimento, salario) VALUES 
('Ana Silva', '111.222.333-44', '03/04/1910', 5000.00),
('Joel Silva', '111.222.333-44', '30/05/2000', 5000.00),
('Maria Silva', '111.222.333-44', '15/04/1999', 6000.00),
('Pedro Silva', '111.222.333-44', '10/05/1950', 10000.00);