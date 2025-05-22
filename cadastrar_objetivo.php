<?php include 'conexao.php'; ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Cadastrar Objetivo</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <h1>Cadastrar Objetivo</h1>
  <form method="post">
    <label>Título:</label><br>
    <input type="text" name="titulo" required><br>

    <label>Tipo:</label><br>
    <select name="tipo" required>
      <option value="organizacional">Organizacional</option>
      <option value="ti">TI</option>
    </select><br>

    <label>Descrição:</label><br>
    <textarea name="descricao" required></textarea><br>

    <button type="submit" name="salvar">Salvar</button>
  </form>
  <a href="index.php">← Voltar</a>

  <?php
  if (isset($_POST['salvar'])) {
    $titulo = $_POST['titulo'];
    $tipo = $_POST['tipo'];
    $descricao = $_POST['descricao'];

    $sql = "INSERT INTO objetivo (titulo, tipo, descricao) VALUES ('$titulo', '$tipo', '$descricao')";
    if ($conexao->query($sql)) {
        echo "<p class='sucesso'>Objetivo cadastrado com sucesso!</p>";
    } else {
        echo "<p class='erro'>Erro: " . $conexao->error . "</p>";
    }
  }
  ?>
</body>
</html>
