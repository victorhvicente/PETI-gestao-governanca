<?php
require_once 'Database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo'] ?? '');
    $tipo = $_POST['tipo'] ?? '';
    $descricao = trim($_POST['descricao'] ?? '');

    $erro = '';
    if ($titulo === '' || ($tipo !== 'organizacional' && $tipo !== 'ti') || $descricao === '') {
        $erro = 'Por favor, preencha todos os campos corretamente.';
    } else {
        try {
            $db = new PDO("mysql:host=localhost;dbname=peti;charset=utf8", "root", "");
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "INSERT INTO objetivo (titulo, tipo, descricao) VALUES (:titulo, :tipo, :descricao)";
            $stmt = $db->prepare($sql);
            $stmt->execute([
                ':titulo' => $titulo,
                ':tipo' => $tipo,
                ':descricao' => $descricao
            ]);
            $sucesso = "Objetivo cadastrado com sucesso!";
        } catch (PDOException $e) {
            $erro = "Erro ao salvar o objetivo: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <title>Cadastro de Objetivo</title>
  <link href="css/missao_visao.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
</head>
<body>
  <div style="text-align:center; margin-bottom: 20px;">
    <h1>Objetivo</h1>
  </div>

  <?php if (!empty($erro)): ?>
    <p style="color:red; font-weight:bold; text-align:center;"><?= htmlspecialchars($erro) ?></p>
  <?php endif; ?>

  <?php if (!empty($sucesso)): ?>
    <p style="color:green; font-weight:bold; text-align:center;"><?= htmlspecialchars($sucesso) ?></p>
  <?php endif; ?>

  <form action="" method="POST" style="max-width:600px; margin:auto; background:#fff; padding:25px; border-radius:12px; box-shadow:0 6px 18px rgba(0,0,0,0.1);">
    <label for="titulo">Título:</label><br />
    <input type="text" id="titulo" name="titulo" maxlength="100" required /><br /><br />

    <label for="tipo">Tipo:</label><br />
    <select name="tipo" id="tipo" required>
      <option value="">--Selecione--</option>
      <option value="organizacional">Organizacional</option>
      <option value="ti">TI</option>
    </select><br /><br />

    <label for="descricao">Descrição:</label><br />
    <textarea id="descricao" name="descricao" rows="4" required></textarea><br />

    <button type="submit" style="width: 100%; margin-top: 20px; background-color: #4a90e2; color: white; padding: 12px; font-size: 1.1rem; border-radius: 8px; border: none; cursor: pointer;">Salvar</button>
  </form>
</body>
</html>
