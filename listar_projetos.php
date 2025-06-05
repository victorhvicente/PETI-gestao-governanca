<?php include 'conexao.php'; ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar Itens</title>

    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h2>Itens Cadastrados</h2>

        <!-- Missão e Visão -->
        <h3>Missão e Visão</h3>
        <table border="1">
            <tr>
                <th>Missão</th>
                <th>Visão</th>
                <th>Ação</th>
            </tr>
            <?php
            $result = $conn->query("SELECT * FROM organizacao");
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['missao']}</td>
                        <td>{$row['visao']}</td>
                        <td>
                            <a href='editar.php?tipo=organizacao&id={$row['id']}'>Editar</a> |
                            <a href='excluir.php?tipo=organizacao&id={$row['id']}'>Excluir</a>
                        </td>
                    </tr>";
            }
            ?>
        </table>

        <!-- Objetivos -->
        <h3>Objetivos</h3>
        <table border="1">
            <tr>
                <th>Tipo</th>
                <th>Descrição</th>
                <th>Ação</th>
            </tr>
            <?php
            $result = $conn->query("SELECT * FROM objetivo");
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['tipo']}</td>
                        <td>{$row['descricao']}</td>
                        <td>
                            <a href='editar.php?tipo=objetivo&id={$row['id']}'>Editar</a> |
                            <a href='excluir.php?tipo=objetivo&id={$row['id']}'>Excluir</a>
                        </td>
                    </tr>";
            }
            ?>
        </table>

        <!-- Projetos -->
        <h3>Projetos de TI</h3>
        <table border="1">
            <tr>
                <th>Nome do Projeto</th><th>Objetivo</th><th>Responsável</th><th>Custo</th><th>Prazo</th><th>Ação</th>
            </tr>
            <?php
            $result = $conn->query("SELECT p.*, o.descricao AS objetivo FROM projeto p JOIN objetivo o ON p.objetivo_id = o.id");
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['nome']}</td>
                        <td>{$row['objetivo']}</td>
                        <td>{$row['responsavl']}</td>
                        <td>R$ {$row['custo']}</td>
                        <td>{$row['prazo']}</td>
                        <td>
                            <a href='editar.php?tipo=projeto&id={$row['id']}'>Editar</a> | 
                            <a href='excluir.php?tipo=projeto&id={$row['id']}'>Excluir</a>
                        </td>
                    </tr>";
            }
            ?>
        </table>

        <!-- Botão de Voltar -->
        <div class="back-button-container">
            <a href="index.php" class="back-button">Voltar para a Página Inicial</a>
        </div>
    </div>
</body>
</html>
