<?php
// Incluir o arquivo de conexão
include '../config/conexao.php';

// Inicializar a variável de mensagem e tipo
$mensagem = '';
$tipo = '';

// Inicializar variáveis para manter os dados no formulário
$nome = '';
$cpf = '';
$data_ncto = '';
$salario = '';

// Verificar se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capturar os dados do formulário
    $nome = $_POST['nome'];
    $cpf = $_POST['cpf'];
    $data_ncto = $_POST['data_ncto'];
    $salario = $_POST['salario'];

    try {
        // Verificar se o CPF já está cadastrado
        $sql = "SELECT * FROM funcionarios WHERE cpf = :cpf";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':cpf', $cpf);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            // Se o CPF já existir no banco de dados
            $mensagem = "CPF já cadastrado. Por favor, use outro CPF.";
            $tipo = "error";
        } else {
            // Caso o CPF não exista, inserir o novo funcionário
            $sql = "INSERT INTO funcionarios (nome, cpf, data_ncto, salario) 
                    VALUES (:nome, :cpf, :data_ncto, :salario)";
            
            // Verifique se a variável $pdo foi definida corretamente no arquivo de conexão
            if (isset($pdo)) {
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':nome', $nome);
                $stmt->bindParam(':cpf', $cpf);
                $stmt->bindParam(':data_ncto', $data_ncto);
                $stmt->bindParam(':salario', $salario);

                // Executar a inserção
                if ($stmt->execute()) {
                    // Se inserido com sucesso, atribui a mensagem de sucesso
                    $mensagem = "Funcionário cadastrado com sucesso!";
                    $tipo = "success";  // Classe para estilizar a mensagem
                } else {
                    // Se houver erro no cadastro
                    $mensagem = "Erro ao cadastrar o funcionário.";
                    $tipo = "error";  // Classe para estilizar a mensagem de erro
                }
            } else {
                // Se a conexão falhou
                $mensagem = "Erro: Conexão com o banco de dados não estabelecida.";
                $tipo = "error";  // Erro de conexão
            }
        }
    } catch (PDOException $e) {
        // Exibir erro de exceção
        $mensagem = "Erro: " . $e->getMessage();
        $tipo = "error";  // Caso de exceção
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Cadastrar Funcionário - Sistema de Gerenciamento</title>
    <link rel="stylesheet" href="../css/styles.css" />
  </head>
  <body>
    <div class="container">
      <header>
        <h1>Cadastrar Novo Funcionário</h1>
      </header>

      <main>
        <div class="form-card">
          <!-- Exibir a mensagem, se houver -->
          <?php if ($mensagem): ?>
            <div class="alert <?php echo $tipo; ?>">
              <?php echo $mensagem; ?>
            </div>
          <?php endif; ?>

          <form action="cadastrar.php" method="POST">
            <div class="form-group">
              <label for="nome">Nome:</label>
              <input type="text" id="nome" name="nome" value="<?php echo $nome; ?>" required />
            </div>

            <div class="form-group">
              <label for="cpf">CPF:</label>
              <input type="text" id="cpf" name="cpf" maxlength="14" value="<?php echo $cpf; ?>" required />
            </div>

            <div class="form-group">
              <label for="data_ncto">Data de Nascimento:</label>
              <input type="date" id="data_ncto" name="data_ncto" value="<?php echo $data_ncto; ?>" required />
            </div>

            <div class="form-group">
              <label for="salario">Salário (R$):</label>
              <input type="number" id="salario" name="salario" value="<?php echo $salario; ?>" step="0.01" min="0" required />
            </div>

            <div class="form-buttons">
              <a href="../index.html" class="btn btn-secondary">Voltar</a>
              <button type="submit" class="btn">Cadastrar</button>
            </div>
          </form>
        </div>
      </main>

      <footer>
        <p>&copy; 2025 Sistema de Gerenciamento de Funcionários</p>
      </footer>
    </div>
  </body>
</html>
