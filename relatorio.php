<?php
session_start();
include 'conexao.php';

// Validação robusta do ID
if (!isset($_GET['id'])) {
    $_SESSION['erro'] = "ID do projeto não informado.";
    header("Location: listar_projetos.php");
    exit;
}

$projeto_id = (int)$_GET['id'];
if ($projeto_id <= 0) {
    $_SESSION['erro'] = "ID do projeto inválido.";
    header("Location: listar_projetos.php");
    exit;
}

// Consulta principal do projeto com tratamento de erros
try {
    $sql_projeto = "SELECT p.*, o.tipo AS objetivo_tipo, o.descricao AS objetivo_descricao 
                   FROM projeto p
                   LEFT JOIN objetivo o ON p.objetivo_id = o.id
                   WHERE p.id = ?";
    $stmt_projeto = $conn->prepare($sql_projeto);
    
    if (!$stmt_projeto) {
        throw new Exception("Erro na preparação da consulta: " . $conn->error);
    }
    
    $stmt_projeto->bind_param("i", $projeto_id);
    $stmt_projeto->execute();
    $result_projeto = $stmt_projeto->get_result();

    if ($result_projeto->num_rows === 0) {
        throw new Exception("Projeto não encontrado com o ID: " . $projeto_id);
    }

    $projeto = $result_projeto->fetch_assoc();

    // Consulta para objetivos vinculados via objetivo_projeto
    $sql_objetivos_projeto = "SELECT o.tipo, op.descricao 
                             FROM objetivo_projeto op
                             JOIN objetivo o ON op.objetivo_id = o.id
                             WHERE op.projeto_id = ?";
    $stmt_objetivos = $conn->prepare($sql_objetivos_projeto);
    $stmt_objetivos->bind_param("i", $projeto_id);
    $stmt_objetivos->execute();
    $result_objetivos = $stmt_objetivos->get_result();
    $objetivos_projeto = $result_objetivos->fetch_all(MYSQLI_ASSOC);

    // Consulta para missões
    $sql_missoes = "SELECT missao FROM missao_projeto WHERE projeto_id = ?";
    $stmt_missoes = $conn->prepare($sql_missoes);
    $stmt_missoes->bind_param("i", $projeto_id);
    $stmt_missoes->execute();
    $result_missoes = $stmt_missoes->get_result();
    $missoes = $result_missoes->fetch_all(MYSQLI_ASSOC);

    // Consulta para visões
    $sql_visoes = "SELECT visao FROM visao_projeto WHERE projeto_id = ?";
    $stmt_visoes = $conn->prepare($sql_visoes);
    $stmt_visoes->bind_param("i", $projeto_id);
    $stmt_visoes->execute();
    $result_visoes = $stmt_visoes->get_result();
    $visoes = $result_visoes->fetch_all(MYSQLI_ASSOC);

} catch (Exception $e) {
    $_SESSION['erro'] = $e->getMessage();
    header("Location: listar_projetos.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório do Projeto - <?= htmlspecialchars($projeto['nome'] ?? 'Projeto não encontrado') ?></title>
    <style>
        /* Mantenha seus estilos CSS aqui */
        body { font-family: Arial, sans-serif; line-height: 1.6; margin: 0; padding: 20px; background-color: #f5f7fa; }
        .container { max-width: 800px; margin: 20px auto; padding: 30px; background: #fff; box-shadow: 0 0 10px rgba(0,0,0,0.1); border-radius: 8px; }
        h2 { color: #2c3e50; border-bottom: 2px solid #3498db; padding-bottom: 10px; margin-bottom: 20px; }
        .info-box { background: #f9f9f9; padding: 20px; margin-bottom: 20px; border-left: 4px solid #3498db; border-radius: 4px; }
        .info-label { font-weight: bold; color: #2c3e50; font-size: 18px; margin-bottom: 10px; }
        .back-button { display: inline-block; margin-top: 20px; padding: 10px 20px; background: #3498db; color: white; text-decoration: none; border-radius: 4px; }
        .back-button:hover { background: #2980b9; }
        ul { padding-left: 20px; }
        li { margin-bottom: 8px; }
        .valor-monetario { color: #27ae60; font-weight: bold; }
        .data { color: #7f8c8d; }
        .sem-dados { color: #95a5a6; font-style: italic; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Relatório do Projeto: <?= htmlspecialchars($projeto['nome'] ?? 'Projeto não encontrado') ?></h2>
        
        <div class="info-box">
            <p class="info-label">Informações Básicas</p>
            <p><strong>Responsável:</strong> <?= htmlspecialchars($projeto['responsavel'] ?? 'Não informado') ?></p>
            <p><strong>Custo:</strong> <span class="valor-monetario">R$ <?= isset($projeto['custo']) ? number_format($projeto['custo'], 2, ',', '.') : '0,00' ?></span></p>
            <p><strong>Prazo:</strong> <span class="data"><?= isset($projeto['prazo']) ? date('d/m/Y', strtotime($projeto['prazo'])) : 'Não definido' ?></span></p>
        </div>

        <div class="info-box">
            <p class="info-label">Missões</p>
            <?php if (!empty($missoes)): ?>
                <ul>
                    <?php foreach ($missoes as $missao): ?>
                        <li><?= htmlspecialchars($missao['missao']) ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="sem-dados">Não informado</p>
            <?php endif; ?>
        </div>

        <div class="info-box">
            <p class="info-label">Visões</p>
            <?php if (!empty($visoes)): ?>
                <ul>
                    <?php foreach ($visoes as $visao): ?>
                        <li><?= htmlspecialchars($visao['visao']) ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="sem-dados">Não informado</p>
            <?php endif; ?>
        </div>

        <div class="info-box">
            <p class="info-label">Objetivo Principal</p>
            <?php if (!empty($projeto['objetivo_tipo'])): ?>
                <p><strong>Tipo:</strong> <?= ucfirst(htmlspecialchars($projeto['objetivo_tipo'])) ?></p>
                <p><strong>Descrição:</strong> <?= htmlspecialchars($projeto['objetivo_descricao'] ?? 'Não informado') ?></p>
            <?php else: ?>
                <p class="sem-dados">Não definido</p>
            <?php endif; ?>
        </div>

        <div class="info-box">
            <p class="info-label">Objetivos Adicionais</p>
            <?php if (!empty($objetivos_projeto)): ?>
                <ul>
                    <?php foreach ($objetivos_projeto as $objetivo): ?>
                        <li>
                            <strong><?= ucfirst(htmlspecialchars($objetivo['tipo'])) ?>:</strong>
                            <?= htmlspecialchars($objetivo['descricao']) ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="sem-dados">Nenhum objetivo adicional cadastrado</p>
            <?php endif; ?>
        </div>

        <a href="listar_projetos.php" class="back-button">Voltar para Listar Projetos</a>
    </div>
</body>
</html>