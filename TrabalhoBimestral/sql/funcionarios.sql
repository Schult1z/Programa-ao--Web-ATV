-- Criação da tabela de funcionários
CREATE TABLE funcionarios (
  matricula INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  cpf VARCHAR(14) NOT NULL,
  data_ncto DATE NOT NULL,
  salario DECIMAL(10, 2) NOT NULL
);

-- Inserção de dados de exemplo
INSERT INTO funcionarios (nome, cpf, data_ncto, salario) VALUES 
('Ana Silva', '111.222.333-44', '1910-04-03', 5000.00),
('Joel Silva', '111.222.333-55', '2000-05-30', 5000.00),
('Maria Silva', '111.222.333-66', '1999-04-15', 6000.00),
('Gabriel Schultz', '111.222.333-88', '2005-11-26', 8000.00),
('Pedro Silva', '111.222.333-99', '1950-05-10', 10000.00),
('Eros Netto Antunes', '111.222.333-77', '2004-02-10', 100000.00);
