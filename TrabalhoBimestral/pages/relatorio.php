<?php
// Incluir o arquivo de conexão
include '../config/conexao.php';

// Inicializar variáveis
$mensagem = '';
$tipo = '';
$funcionarios = [];
$data_atual = date("Y-m-d");
$mes_atual = date("m");

// Array com os nomes dos meses em português
$meses = [
    1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março', 4 => 'Abril',
    5 => 'Maio', 6 => 'Junho', 7 => 'Julho', 8 => 'Agosto',
    9 => 'Setembro', 10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro'
];

// Obter o nome do mês atual em português
$mes_atual_nome = $meses[(int)$mes_atual];

// Recuperar os funcionários do banco de dados
try {
    $sql = "SELECT * FROM funcionarios";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $funcionarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $mensagem = "Erro: " . $e->getMessage();
    $tipo = "error";
}

// Função para calcular o bônus de 10% do salário
function calcularBonus($salario, $data_nascimento, $mes_atual) {
    $mes_nascimento = date("m", strtotime($data_nascimento));
    // Verifica se o aniversário é no mês atual
    if ($mes_nascimento == $mes_atual) {
        return $salario * 0.10; // Bônus de 10%
    }
    return 0; // Sem bônus
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Relatório de Funcionários - Sistema de Gerenciamento</title>
    <link rel="stylesheet" href="../css/styles.css" />
  </head>
  <body>
    <div class="container">
      <header>
        <h1>Relatório de Funcionários</h1>
        <a href="../index.html" class="btn btn-secondary">Voltar</a>
      </header>

      <main>
        <div class="report-info">
          <p>Mês atual: <?php echo ucfirst($mes_atual_nome); ?></p>
          <p>Funcionários aniversariantes do mês recebem bônus de 10%</p>
        </div>

        <?php if ($mensagem): ?>
          <div class="alert <?php echo $tipo; ?>">
            <?php echo $mensagem; ?>
          </div>
        <?php endif; ?>

        <div class="table-container">
          <table>
            <thead>
              <tr>
                <th>Nome</th>
                <th>CPF</th>
                <th>Data Nascimento</th>
                <th>Salário R$</th>
                <th>Bônus R$</th>
                <th>Total R$</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($funcionarios)): ?>
                <tr>
                  <td colspan="6" class="no-data">Nenhum funcionário cadastrado.</td>
                </tr>
              <?php else: ?>
                <?php
                // Exibir os funcionários na tabela
                foreach ($funcionarios as $funcionario) {
                    $bonus = calcularBonus($funcionario['salario'], $funcionario['data_nascimento'], $mes_atual);
                    $total = $funcionario['salario'] + $bonus;
                ?>
                <tr>
                  <td><?php echo $funcionario['nome']; ?></td>
                  <td><?php echo $funcionario['cpf']; ?></td>
                  <td><?php echo date("d/m/Y", strtotime($funcionario['data_nascimento'])); ?></td>
                  <td><?php echo number_format($funcionario['salario'], 2, ',', '.'); ?></td>
                  <td><?php echo $bonus > 0 ? number_format($bonus, 2, ',', '.') : '-'; ?></td>
                  <td><?php echo number_format($total, 2, ',', '.'); ?></td>
                </tr>
                <?php } ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </main>

      <footer>
        <p>&copy; 2025 Sistema de Gerenciamento de Funcionários</p>
      </footer>
    </div>
  </body>
</html>
