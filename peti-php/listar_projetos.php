<?php
include 'conexao.php';

// Ativa exibição de erros para ajudar no debug (remova em produção)
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Listar Projetos</title>
    <link rel="stylesheet" href="css/style.css" />
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <a href="index.php" class="logo">ProjetoApp</a>
            <ul class="nav-links">
                <li><a href="listar_projetos.php" class="active">Listar Projetos</a></li>
                <li><a href="cadastrar_projeto.php">Cadastrar Projeto</a></li>
                <li><a href="relatorio.php">Relatório</a></li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <h2 class="page-title">Projetos Cadastrados</h2>

        <div class="card-container">
            <?php
            $sql = "SELECT 
                        p.*,
                        o.tipo AS objetivo_tipo,
                        o.descricao AS objetivo_descricao,
                        (SELECT GROUP_CONCAT(missao SEPARATOR '; ') FROM missao_projeto mp WHERE mp.projeto_id = p.id) AS missoes,
                        (SELECT GROUP_CONCAT(visao SEPARATOR '; ') FROM visao_projeto vp WHERE vp.projeto_id = p.id) AS visoes
                    FROM projeto p
                    LEFT JOIN objetivo o ON p.objetivo_id = o.id";

            $result = $conn->query($sql);

            if (!$result) {
                echo "<p>Erro na consulta: " . $conn->error . "</p>";
            } else if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $missoes_raw = htmlspecialchars($row['missoes'] ?? 'Não informado');
                    $visoes_raw = htmlspecialchars($row['visoes'] ?? 'Não informado');

                    $missoes_formatadas = nl2br(str_replace('; ', "<br>", $missoes_raw));
                    $visoes_formatadas = nl2br(str_replace('; ', "<br>", $visoes_raw));

                    echo '<div class="card">';
                    echo '<h3>' . htmlspecialchars($row['nome']) . '</h3>';

                    echo '<p title="' . $missoes_raw . '"><strong>Missão:</strong><br>' . $missoes_formatadas . '</p>';
                    echo '<p title="' . $visoes_raw . '"><strong>Visão:</strong><br>' . $visoes_formatadas . '</p>';

                    echo '<p><strong>Tipo do Objetivo:</strong> ' . htmlspecialchars(ucfirst($row['objetivo_tipo'] ?? 'Não definido')) . '</p>';
                    echo '<p><strong>Descrição do Objetivo:</strong> ' . nl2br(htmlspecialchars($row['objetivo_descricao'] ?? 'Não informado')) . '</p>';

                    echo '<p><strong>Responsável:</strong> ' . htmlspecialchars($row['responsavel']) . '</p>';
                    echo '<p><strong>Custo:</strong> R$ ' . number_format($row['custo'], 2, ',', '.') . '</p>';
                    echo '<p><strong>Prazo:</strong> ' . htmlspecialchars($row['prazo']) . '</p>';

                    echo '<div>';
                    echo '<a class="btn" href="editar.php?tipo=projeto&id=' . $row['id'] . '">Editar</a> ';
                    echo '<a class="btn btn-danger" href="excluir.php?tipo=projeto&id=' . $row['id'] . '" onclick="return confirm(\'Tem certeza que deseja excluir este projeto?\')">Excluir</a>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo '<p>Nenhum projeto cadastrado.</p>';
            }
            ?>
        </div>

        <div class="back-button-container">
            <a href="index.php" class="back-button">← Voltar para a Página Inicial</a>
        </div>
    </div>
</body>
</html>
