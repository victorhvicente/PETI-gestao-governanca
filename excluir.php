<?php include 'conexao.php'; ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Excluir Item</title>
</head>
<body>
    <div class="container">
        <h2>Excluir Item</h2>

        <?php
        if (isset($_GET['tipo']) && isset($_GET['id'])) {
            $tipo = $_GET['tipo'];
            $id = (int)$_GET['id'];

            if ($tipo == 'organizacao') {
                $conn->query("DELETE FROM organizacao WHERE id = $id");
                echo "<p>Item excluído com sucesso!</p>";
            } elseif ($tipo == 'objetivo') {
                $result = $conn->query("SELECT COUNT(*) AS qtd FROM projeto WHERE objetivo_id = $id");
                $row = $result->fetch_assoc();

                if ($row['qtd'] > 0) {
                    echo "<p>Não é possível excluir: existem projetos vinculados a este objetivo.</p>";
                } else {
                    $conn->query("DELETE FROM objetivo WHERE id = $id");
                    echo "<p>Item excluído com sucesso!</p>";
                }
            } elseif ($tipo == 'projeto') {
                $conn->query("DELETE FROM projeto WHERE id = $id");
                echo "<p>Item excluído com sucesso!</p>";
                header ("Location: listar_projetos.php");
            } else {
                echo "<p>Tipo inválido.</p>";
            }
        } else {
            echo "<p>Parâmetros inválidos.</p>";
        }
        ?>

        <div class="back-button-container">
            <a href="listar_projetos.php" class="back-button">Voltar para Projetos</a>
        </div>
    </div>
</body>
</html>
