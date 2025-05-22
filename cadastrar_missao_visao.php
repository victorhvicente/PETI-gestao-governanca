<?php
require_once 'Database.php';

// Só processa o formulário se for POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $missao = trim($_POST['missao'] ?? '');
    $visao = trim($_POST['visao'] ?? '');

    if ($nome === '' || $missao === '' || $visao === '') {
        $erro = 'Por favor, preencha todos os campos.';
    } else {
        try {
            $db = new PDO("mysql:host=localhost;dbname=peti;charset=utf8", "root", "");
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "INSERT INTO organizacao (nome, missao, visao) VALUES (:nome, :missao, :visao)";
            $stmt = $db->prepare($sql);
            $stmt->execute([
                ':nome' => $nome,
                ':missao' => $missao,
                ':visao' => $visao
            ]);
            $sucesso = "Dados salvos com sucesso!";
        } catch (PDOException $e) {
            $erro = "Erro ao salvar os dados: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <link href="css/missao_visao.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
  <title>Cadastro de Missão e Visão</title>
</head>
<body>
  <div>
    <h1>MISSÃO</h1>
    <h3>E</h3>
    <h1>VISÃO</h1>
  </div>

  <?php if (!empty($erro)): ?>
    <p style="color:red; text-align:center; font-weight:bold;"><?= htmlspecialchars($erro) ?></p>
  <?php endif; ?>

  <?php if (!empty($sucesso)): ?>
    <p style="color:green; text-align:center; font-weight:bold;"><?= htmlspecialchars($sucesso) ?></p>
  <?php endif; ?>

  <form action="" method="POST">
    <label for="nome">Nome da Organização:</label><br />
    <input type="text" id="nome" name="nome" required /><br /><br />

    <label for="missao">Missão:</label><br />
    <textarea name="missao" id="missao" rows="4" required></textarea><br />

    <label for="visao">Visão:</label><br />
    <textarea name="visao" id="visao" rows="4" required></textarea><br />

    <button type="submit">Salvar</button>
  </form>
</body>
</html>
