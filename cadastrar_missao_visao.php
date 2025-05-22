<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <link href="missao_visao.css" rel="stylesheet"></link>
   <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <title>Cadastro de Missão e Visão</title>
</head>
<body>
  <h1>Cadastro de Missão e Visão</h1>
  <form action="salvar_missao_visao.php" method="POST">
    <label for="missao">Missão:</label><br>
    <textarea name="missao" id="missao" rows="4" required></textarea><br>

    <label for="visao">Visão:</label><br>
    <textarea name="visao" id="visao" rows="4" required></textarea><br>

    <button type="submit">Salvar</button>
  </form>
</body>
</html>
