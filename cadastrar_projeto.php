<?php include 'conexao.php'; ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Projeto</title>

    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h2>Cadastrar Projeto</h2>
        <form method="post">
            <label>Nome do Projeto:</label><br>
            <input type="text" name="nome" required><br>

            <label>Objetivo do Projeto:</label><br>
            <select name="objetivo_id" required>
                <?php
                $result = $conn->query("SELECT * FROM objetivo");
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='{$row['id']}'>{$row['descricao']}</option>";
                }
                ?>
            </select><br>

            <label>Responsável:</label><br>
            <input type="text" name="responsavl" required><br>

            <label>Custo (R$):</label><br>
            <input type="number" name="custo" step="0.01" required><br>

            <label>Prazo:</label><br>
            <input type="date" name="prazo" required><br>

            <input type="submit" value="Cadastrar Projeto">
        </form>

        <?php
        if ($_POST) {
            $nome = $_POST['nome'];
            $objetivo_id = $_POST['objetivo_id'];
            $responsavl = $_POST['responsavl'];
            $custo = $_POST['custo'];
            $prazo = $_POST['prazo'];

            // Inserir no banco de dados
            $conn->query("INSERT INTO projeto (nome, objetivo_id, responsavl, custo, prazo) VALUES ('$nome', '$objetivo_id', '$responsavl', '$custo', '$prazo')");
            echo "<p>Projeto cadastrado com sucesso!</p>";
        }
        ?>

        <!-- Botão de Voltar -->
        <div class="back-button-container">
            <a href="index.php" class="back-button">Voltar para a Página Inicial</a>
        </div>
    </div>
</body>
</html>
