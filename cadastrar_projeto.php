<?php include 'conexao.php'; ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Cadastrar Projeto</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <h1>Cadastrar Projeto</h1>
  <form method="post">
    <label>Nome do projeto:</label><br>
    <input type="text" name="nome" required><br>

    <label>Objetivo relacionado:</label><br>
    <select name="id_objetivo" required>
      <?php
      $result = $conexao->query("SELECT * FROM objetivo");
      while ($row = $result->fetch_assoc()) {
          echo "<option value='{$row['id_objetivo']}'>{$row['titulo']}</option>";
      }
      ?>
    </select><br>

    <label>Responsável:</label><br>
    <input type="text" name="responsavel" required><br>

    <label>Custo (R$):</label><br>
    <input type="number" step="0.01" name="custo" required><br>

    <label>Prazo:</label><br>
    <input type="date" name="prazo" required><br>

    <label>Status:</label><br>
    <select name="status" required>
      <option value="planejado">Planejado</option>
      <option value="em_andamento">Em andamento</option>
      <option value="concluido">Concluído</option>
      <option value="cancelado">Cancelado</option>
    </select><br>

    <button type="submit" name="salvar">Salvar Projeto</button>
  </form>
  <a href="index.php">← Voltar</a>

  <?php
  if (isset($_POST['salvar'])) {
    $nome = $_POST['nome'];
    $id_objetivo = $_POST['id_objetivo'];
    $responsavel = $_POST['responsavel'];
    $custo = $_POST['custo'];
    $prazo = $_POST['prazo'];
    $status = $_POST['status'];

    $sql = "INSERT INTO projeto (nome, id_objetivo, responsavel, custo, prazo, status)
            VALUES ('$nome', $id_objetivo, '$responsavel', $custo, '$prazo', '$status')";
    if ($conexao->query($sql)) {
        echo "<p class='sucesso'>Projeto cadastrado com sucesso!</p>";
    } else {
        echo "<p class='erro'>Erro: " . $conexao->error . "</p>";
    }
  }
  ?>
</body>
</html>
