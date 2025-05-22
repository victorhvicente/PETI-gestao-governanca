<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <title>Cadastro de Projeto</title>
  <link href="css/projeto.css" rel="stylesheet">
</head>
<body>
  <h1>Projeto</h1>
  <form action="/cadastrar_projeto" method="POST" class="form-grid">
    <div class="form-group">
      <label for="nome">Nome do Projeto:</label>
      <input type="text" id="nome" name="nome" maxlength="100" required />
    </div>

    <div class="form-group">
      <label for="id_objetivo">ID do Objetivo:</label>
      <input type="number" id="id_objetivo" name="id_objetivo" required />
    </div>

    <div class="form-group">
      <label for="responsavel">Responsável:</label>
      <input type="text" id="responsavel" name="responsavel" maxlength="100" required />
    </div>

    <div class="form-group">
      <label for="custo">Custo (R$):</label>
      <input type="number" id="custo" name="custo" step="0.01" min="0" required />
    </div>

    <div class="form-group">
      <label for="prazo">Prazo (Data):</label>
      <input type="date" id="prazo" name="prazo" required />
    </div>

    <div class="form-group button-group">
      <button type="submit">Cadastrar</button>
    </div>
  </form>
</body>



</body>
</html>
