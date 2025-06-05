<?php include 'conexao.php'; ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Objetivo</title>

    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h2>Cadastrar Objetivo</h2>
        <form method="post">
            <label>Tipo de Objetivo:</label><br>
            <select name="tipo">
                <option value="organizacional">Organizacional</option>
                <option value="ti">TI</option>
            </select><br>

            <label>Descrição do Objetivo:</label><br>
            <textarea name="descricao" required></textarea><br>

            <input type="submit" value="Salvar Objetivo">
        </form>

        <?php
        if ($_POST) {
            $tipo = $_POST['tipo'];
            $descricao = $_POST['descricao'];

            // Inserção no banco de dados
            $conn->query("INSERT INTO objetivo (tipo, descricao) VALUES ('$tipo', '$descricao')");
            echo "<p>Objetivo cadastrado com sucesso!</p>";
        }

        ?>

        <!-- Botão de Voltar -->
        <div class="back-button-container">
            <a href="index.php" class="back-button">Voltar para a Página Inicial</a>
        </div>
    </div>
</body>
</html>
