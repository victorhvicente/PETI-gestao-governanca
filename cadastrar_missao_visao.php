<?php include 'conexao.php'; ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Missão e Visão</title>

    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h2>Cadastrar Missão e Visão</h2>
        <form method="post">
            <label>Missão:</label><br>
            <textarea name="missao" required></textarea><br>

            <label>Visão:</label><br>
            <textarea name="visao" required></textarea><br>

            <input type="submit" value="Salvar">
        </form>

        <?php
        // Verificar se o formulário foi enviado
        if ($_POST) {
            $missao = $_POST['missao'];
            $visao = $_POST['visao'];

            // Inserir no banco de dados
            $conn->query("INSERT INTO organizacao (missao, visao) VALUES ('$missao', '$visao')");

            // Exibir mensagem de sucesso
            echo "<p>Missão e Visão cadastradas com sucesso!</p>";
        }

        // Buscar a missão e visão cadastradas recentemente
        $result = $conn->query("SELECT * FROM organizacao ORDER BY id DESC LIMIT 1");
        if ($result) {
            $row = $result->fetch_assoc();
            if ($row) {
                echo "<h3>Missão Cadastrada:</h3>";
                echo "<p>{$row['missao']}</p>";
                echo "<h3>Visão Cadastrada:</h3>";
                echo "<p>{$row['visao']}</p>";

                }
        }
        ?>

        <div class="back-button-container">
            <a href="index.php" class="back-button">Voltar para a Página Inicial</a>
        </div>
    </div>
</body>
</html>
