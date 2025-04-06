<?php
// Incluir o arquivo de conexão
include '../config/conexao.php';

// Inicializar variáveis
$mensagem = '';
$tipo = '';
$funcionarios = [];

// Verificar se foi solicitado a exclusão de um funcionário
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        // Preparar o comando SQL para excluir o funcionário
        $sql = "DELETE FROM funcionarios WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            // Exibir mensagem de sucesso
            $mensagem = "Funcionário excluído com sucesso!";
            $tipo = "success";
        } else {
            // Exibir mensagem de erro caso a exclusão falhe
            $mensagem = "Erro ao excluir o funcionário.";
            $tipo = "error";
        }
    } catch (PDOException $e) {
        // Em caso de erro na conexão ou na execução do comando
        $mensagem = "Erro: " . $e->getMessage();
        $tipo = "error";
    }
}

// Recuperar os funcionários do banco de dados
try {
    $sql = "SELECT * FROM funcionarios";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $funcionarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Gerenciar Funcionários - Sistema de Gerenciamento</title>
    <link rel="stylesheet" href="../css/styles.css" />
  </head>
  <body>
    <div class="container">
      <header>
        <h1>Gerenciar Funcionários</h1>
        <a href="../index.html" class="btn btn-secondary">Voltar</a>
      </header>

      <main>
        <!-- Exibir a mensagem, se houver -->
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
                <th>Data de Nascimento</th>
                <th>Salário</th>
                <th>Ações</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($funcionarios)): ?>
                <tr>
                  <td colspan="5" class="no-data">Nenhum funcionário cadastrado.</td>
                </tr>
              <?php else: ?>
                <?php
                // Exibir os funcionários na tabela
                foreach ($funcionarios as $funcionario) {
                ?>
                <tr>
                  <td><?php echo $funcionario['nome']; ?></td>
                  <td><?php echo $funcionario['cpf']; ?></td>
                  <td><?php echo date("d/m/Y", strtotime($funcionario['data_nascimento'])); ?></td>
                  <td>R$ <?php echo number_format($funcionario['salario'], 2, ',', '.'); ?></td>
                  <td class="actions">
                    <a href="editar.php?id=<?php echo $funcionario['id']; ?>" class="btn-icon edit">Editar</a>
                    <a href="gerenciar.php?id=<?php echo $funcionario['id']; ?>" class="btn-icon delete" onclick="return confirm('Você tem certeza que deseja excluir este funcionário?')">Excluir</a>
                  </td>
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
