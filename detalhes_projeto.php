<?php
include 'conexao.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "ID do projeto inválido.";
    exit;
}

$id = intval($_GET['id']);

// ALTERAÇÃO: mudar JOIN para LEFT JOIN para trazer projeto mesmo que objetivo seja NULL
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
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Projeto não encontrado.";
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
    <title>Detalhes do Projeto</title>
    <link rel="stylesheet" href="css/style.css" />
    <style>
        /* Estilo extra para layout tipo PDF */
        .pdf-container {
            background: white;
            padding: 30px;
            margin: 40px auto;
            max-width: 800px;
            border: 1px solid #ccc;
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
            font-family: 'Times New Roman', serif;
            color: #222;
        }
        .pdf-container h2 {
            text-align: center;
            margin-bottom: 25px;
            color: var(--cor-primaria);
        }
        .pdf-section {
            margin-bottom: 20px;
        }
        .pdf-section strong {
            display: block;
            margin-bottom: 8px;
            font-size: 1.1em;
            border-bottom: 1px solid var(--cor-borda);
            padding-bottom: 5px;
        }
    </style>
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

    <div class="container pdf-container">
        <h2><?php echo htmlspecialchars($projeto['nome']); ?></h2>

        <div class="pdf-section">
            <strong>Responsável:</strong>
            <p><?php echo htmlspecialchars($projeto['responsavel']); ?></p>
        </div>

        <div class="pdf-section">
            <strong>Custo:</strong>
            <p>R$ <?php echo number_format($projeto['custo'], 2, ',', '.'); ?></p>
        </div>

        <div class="pdf-section">
            <strong>Prazo:</strong>
            <p><?php echo htmlspecialchars($projeto['prazo']); ?></p>
        </div>

        <div class="pdf-section">
            <strong>Objetivo:</strong>
            <p><em><?php echo htmlspecialchars(ucfirst($projeto['objetivo_tipo'] ?? 'Não definido')); ?></em></p>
            <p><?php echo nl2br(htmlspecialchars($projeto['objetivo_descricao'] ?? 'Não informado')); ?></p>
        </div>

        <div class="pdf-section">
            <strong>Missões:</strong>
            <p><?php echo $missoes_formatadas; ?></p>
        </div>

        <div class="pdf-section">
            <strong>Visões:</strong>
            <p><?php echo $visoes_formatadas; ?></p>
        </div>

        <div style="text-align:center; margin-top: 30px;">
            <a href="relatorio.php" class="btn">Voltar ao Relatório</a>
        </div>
    </div>
</body>
</html>
