<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <link href="css/missao_visao.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <title>Cadastro de Missão e Visão</title>
</head>
<body>
  <div>
    <h1>MISSÃO</h1>
    <h3>e</h3>
    <h1>VISÃO</h1>
  </div>

  <form action="salvar_missao_visao.php" method="POST">

    <label for="organizacao">Nome da Organização:</label><br>
    <input type="text" id="organizacao" name="organizacao" required><br><br>

    <label for="missao">Missão:</label><br>
    <textarea name="missao" id="missao" rows="4" required></textarea><br>

    <label for="visao">Visão:</label><br>
    <textarea name="visao" id="visao" rows="4" required></textarea><br>

    <button type="submit">Salvar</button>
  </form>
</body>
</html>
