<?php include 'conexao.php'; ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Projetos</title>

    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h2>Relatório de Projetos</h2>
        <?php
        $totalProjetos = $conn->query("SELECT COUNT(*) as total FROM projeto")->fetch_assoc()['total'];
        $totalCusto = $conn->query("SELECT SUM(custo) as total FROM projeto")->fetch_assoc()['total'];

        echo "<p>Total de Projetos: $totalProjetos</p>";
        echo "<p>Custo Total dos Projetos: R$ $totalCusto</p>";
        ?>

        <!-- Botão de Voltar -->
        <div class="back-button-container">
            <a href="index.php" class="back-button">Voltar para a Página Inicial</a>
        </div>
    </div>
</body>
</html>
