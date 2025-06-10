<?php
include 'conexao.php';

// Validação do parâmetro 'id' recebido via GET
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<p style='color:red;'>ID do projeto inválido. Por favor, informe um ID válido na URL, ex: relatorio.php?id=1</p>";
    echo '<p><a href="listar_projetos.php">Voltar para Listar Projetos</a></p>';
    exit;
}

$projeto_id = (int) $_GET['id'];

// Consulta para buscar o projeto e seus dados relacionados
$sql = "SELECT 
            p.*,
            o.tipo AS objetivo_tipo,
            o.descricao AS objetivo_descricao,
            (SELECT GROUP_CONCAT(missao SEPARATOR '; ') FROM missao_projeto mp WHERE mp.projeto_id = p.id) AS missoes,
            (SELECT GROUP_CONCAT(visao SEPARATOR '; ') FROM visao_projeto vp WHERE vp.projeto_id = p.id) AS visoes
        FROM projeto p
        LEFT JOIN objetivo o ON p.objetivo_id = o.id
        WHERE p.id = ?";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Erro na preparação da consulta: " . $conn->error);
}

$stmt->bind_param("i", $projeto_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<p style='color:red;'>Projeto não encontrado para o ID: " . htmlspecialchars($projeto_id) . "</p>";
    echo '<p><a href="listar_projetos.php">Voltar para Listar Projetos</a></p>';
    exit;
}

$projeto = $result->fetch_assoc();

$missoes_raw = htmlspecialchars($projeto['missoes'] ?? 'Não informado');
$visoes_raw = htmlspecialchars($projeto['visoes'] ?? 'Não informado');

$missoes_formatadas = nl2br(str_replace('; ', "<br>", $missoes_raw));
$visoes_formatadas = nl2br(str_replace('; ', "<br>", $visoes_raw));
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Detalhes do Projeto - <?= htmlspecialchars($projeto['nome']) ?></title>
    <link rel="stylesheet" href="css/style.css" />
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <a href="index.php" class="logo">ProjetoApp</a>
            <ul class="nav-links">
                <li><a href="listar_projetos.php">Listar Projetos</a></li>
                <li><a href="cadastrar_projeto.php">Cadastrar Projeto</a></li>
                <li><a href="relatorio.php">Relatório</a></li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <h2>Detalhes do Projeto: <?= htmlspecialchars($projeto['nome']) ?></h2>

        <p><strong>Responsável:</strong> <?= htmlspecialchars($projeto['responsavel']) ?></p>
        <p><strong>Custo:</strong> R$ <?= number_format($projeto['custo'], 2, ',', '.') ?></p>
        <p><strong>Prazo:</strong> <?= htmlspecialchars($projeto['prazo']) ?></p>

        <p><strong>Missão:</strong><br><?= $missoes_formatadas ?></p>
        <p><strong>Visão:</strong><br><?= $visoes_formatadas ?></p>

        <p><strong>Tipo do Objetivo:</strong> <?= htmlspecialchars(ucfirst($projeto['objetivo_tipo'] ?? 'Não definido')) ?></p>
        <p><strong>Descrição do Objetivo:</strong><br><?= nl2br(htmlspecialchars($projeto['objetivo_descricao'] ?? 'Não informado')) ?></p>

        <div class="back-button-container">
            <a href="listar_projetos.php" class="back-button">← Voltar para Listar Projetos</a>
        </div>
    </div>
</body>
</html>
