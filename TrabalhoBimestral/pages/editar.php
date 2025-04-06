<?php
// Incluir o arquivo de conexão
include '../config/conexao.php';

// Inicializar variáveis
$mensagem = '';
$tipo = '';
$funcionario = null;

// Verificar se foi passado o id do funcionário para edição
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        // Recuperar os dados do funcionário para preencher o formulário
        $sql = "SELECT * FROM funcionarios WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $funcionario = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verificar se o funcionário foi encontrado
        if (!$funcionario) {
            $mensagem = "Funcionário não encontrado!";
            $tipo = "error";
        }
    } catch (PDOException $e) {
        $mensagem = "Erro: " . $e->getMessage();
        $tipo = "error";
    }
}

// Verificar se o formulário foi enviado para atualizar os dados
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obter os dados do formulário
    $nome = $_POST['nome'];
    $cpf = $_POST['cpf'];
    $data_nascimento = $_POST['data_nascimento'];
    $salario = $_POST['salario'];

    try {
        // Atualizar os dados do funcionário no banco
        $sql = "UPDATE funcionarios SET nome = :nome, cpf = :cpf, data_nascimento = :data_nascimento, salario = :salario WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':cpf', $cpf);
        $stmt->bindParam(':data_nascimento', $data_nascimento);
        $stmt->bindParam(':salario', $salario);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            $mensagem = "Funcionário atualizado com sucesso!";
            $tipo = "success";
        } else {
            $mensagem = "Erro ao atualizar os dados do funcionário.";
            $tipo = "error";
        }
    } catch (PDOException $e) {
        $mensagem = "Erro: " . $e->getMessage();
        $tipo = "error";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Editar Funcionário - Sistema de Gerenciamento</title>
    <link rel="stylesheet" href="../css/styles.css" />
  </head>
  <body>
    <div class="container">
      <header>
        <h1>Editar Funcionário</h1>
      </header>

      <main>
        <!-- Exibir a mensagem, se houver -->
        <?php if ($mensagem): ?>
          <div class="alert <?php echo $tipo; ?>">
            <?php echo $mensagem; ?>
          </div>
        <?php endif; ?>

        <div class="form-card">
          <?php if ($funcionario): ?>
          <form action="editar.php?id=<?php echo $funcionario['id']; ?>" method="POST">
            <div class="form-group">
              <label for="nome">Nome:</label>
              <input
                type="text"
                id="nome"
                name="nome"
                value="<?php echo $funcionario['nome']; ?>"
                required
              />
            </div>

            <div class="form-group">
              <label for="nome">CPF:</label>
              <input
                type="text"
                id="cpf"
                name="cpf"
                maxlength="14"
                value="<?php echo $funcionario['cpf']; ?>"
                required
              />
            </div>

            <div class="form-group">
              <label for="data_nascimento">Data de Nascimento:</label>
              <input
                type="date"
                id="data_nascimento"
                name="data_nascimento"
                value="<?php echo date("Y-m-d", strtotime($funcionario['data_nascimento'])); ?>"
                required
              />
            </div>

            <div class="form-group">
              <label for="salario">Salário (R$):</label>
              <input
                type="number"
                id="salario"
                name="salario"
                step="0.01"
                min="0"
                value="<?php echo number_format($funcionario['salario'], 2, '.', ''); ?>"
                required
              />
            </div>

            <div class="form-buttons">
              <a href="gerenciar.php" class="btn btn-secondary">Voltar</a>
              <button type="submit" class="btn">Salvar Alterações</button>
            </div>
          </form>
          <?php endif; ?>
        </div>
      </main>

      <footer>
        <p>&copy; 2025 Sistema de Gerenciamento de Funcionários</p>
      </footer>
    </div>
  </body>
</html>
