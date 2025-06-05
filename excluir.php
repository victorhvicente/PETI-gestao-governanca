<?php include 'conexao.php'; ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir Item</title>
</head>
<body>
    <div class="container">
        <h2>Excluir Item</h2>
        
        <?php
        if (isset($_GET['tipo']) && isset($_GET['id'])) {
            $tipo = $_GET['tipo'];
            $id = $_GET['id'];

            if ($tipo == 'organizacao') {
                $conn->query("DELETE FROM organizacao WHERE id = $id");
            } elseif ($tipo == 'objetivo') {
                $conn->query("DELETE FROM objetivo WHERE id = $id");
            } elseif ($tipo == 'projeto') {
                $conn->query("DELETE FROM projeto WHERE id = $id");
            }

            echo "<p>Item excluído com sucesso!</p>";
        }
        ?>

        <div class="back-button-container">
            <a href="index.php" class="back-button">Voltar para a Página Inicial</a>
        </div>
    </div>
</body>
</html>
