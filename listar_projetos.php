<?php
include 'conexao.php';

// Configuração para exibição de erros (remover em produção)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Consulta otimizada para listar projetos com todos os relacionamentos
$sql = "SELECT 
            p.id,
            p.nome,
            p.responsavel,
            p.custo,
            p.prazo,
            o.tipo AS objetivo_tipo,
            o.descricao AS objetivo_descricao,
            (SELECT GROUP_CONCAT(missao SEPARATOR '||') FROM missao_projeto WHERE projeto_id = p.id) AS missoes,
            (SELECT GROUP_CONCAT(visao SEPARATOR '||') FROM visao_projeto WHERE projeto_id = p.id) AS visoes,
            (SELECT GROUP_CONCAT(CONCAT(ob.tipo, ': ', op.descricao) SEPARATOR '||') 
             FROM objetivo_projeto op 
             JOIN objetivo ob ON op.objetivo_id = ob.id 
             WHERE op.projeto_id = p.id) AS objetivos_adicionais
        FROM projeto p
        LEFT JOIN objetivo o ON p.objetivo_id = o.id
        ORDER BY p.nome";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar Projetos</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Estilos adicionais para melhorar a visualização */
        .card-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        
        .card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            background: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .card h3 {
            margin-top: 0;
            color: #2c3e50;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        
        .card p {
            margin: 8px 0;
        }
        
        .btn-group {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }
        
        .btn {
            padding: 8px 12px;
            text-decoration: none;
            border-radius: 4px;
            font-size: 14px;
        }
        
        .btn-primary {
            background-color: #3498db;
            color: white;
        }
        
        .btn-danger {
            background-color: #e74c3c;
            color: white;
        }
        
        .info-label {
            font-weight: bold;
            color: #2c3e50;
        }

    </style>
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <ul class="nav-links">
                <li><a href="listar_projetos.php" class="active">Listar Projetos</a></li>
                <li><a href="cadastrar_projeto.php">Cadastrar Projeto</a></li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <h2 class="page-title">Projetos Cadastrados</h2>

        <?php if (!$result): ?>
            <div class="alert alert-danger">Erro na consulta: <?= htmlspecialchars($conn->error) ?></div>
        <?php elseif ($result->num_rows == 0): ?>
            <div class="alert alert-info">Nenhum projeto cadastrado.</div>
        <?php else: ?>
            <div class="card-container">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="card">
                        <h3><?= htmlspecialchars($row['nome']) ?></h3>
                        
                        <p><span class="info-label">Missões:</span><br>
                        <?php 
                            $missoes = !empty($row['missoes']) ? explode('||', $row['missoes']) : ['Não informado'];
                            foreach ($missoes as $missao) {
                                echo '- ' . htmlspecialchars($missao) . '<br>';
                            }
                        ?>
                        </p>
                        
                        <p><span class="info-label">Visões:</span><br>
                        <?php 
                            $visoes = !empty($row['visoes']) ? explode('||', $row['visoes']) : ['Não informado'];
                            foreach ($visoes as $visao) {
                                echo '- ' . htmlspecialchars($visao) . '<br>';
                            }
                        ?>
                        </p>
                        
                        <p><span class="info-label">Objetivo Principal:</span><br>
                        <?php if (!empty($row['objetivo_tipo'])): ?>
                            <strong><?= ucfirst(htmlspecialchars($row['objetivo_tipo'])) ?>:</strong> 
                            <?= htmlspecialchars($row['objetivo_descricao'] ?? 'Sem descrição') ?>
                        <?php else: ?>
                            Não definido
                        <?php endif; ?>
                        </p>
                        
                        <?php if (!empty($row['objetivos_adicionais'])): ?>
                            <p><span class="info-label">Objetivos Adicionais:</span><br>
                            <?php 
                                $objetivos = explode('||', $row['objetivos_adicionais']);
                                foreach ($objetivos as $objetivo) {
                                    echo '- ' . htmlspecialchars($objetivo) . '<br>';
                                }
                            ?>
                            </p>
                        <?php endif; ?>
                        
                        <p><span class="info-label">Responsável:</span> <?= htmlspecialchars($row['responsavel']) ?></p>
                        <p><span class="info-label">Custo:</span> R$ <?= number_format($row['custo'], 2, ',', '.') ?></p>
                        <p><span class="info-label">Prazo:</span> <?= date('d/m/Y', strtotime($row['prazo'])) ?></p>
                        
                        <div class="btn-group">
                            <a href="editar.php?tipo=projeto&id=<?= $row['id'] ?>" class="btn btn-primary">Editar</a>
                            <a href="excluir.php?tipo=projeto&id=<?= $row['id'] ?>" 
                               class="btn btn-danger" 
                               onclick="return confirm('Tem certeza que deseja excluir este projeto?')">Excluir</a>
                            <a href="relatorio.php?id=<?= $row['id'] ?>" class="btn">Ver Relatório</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>

        <div class="back-button-container">
            <a href="index.php" class="back-button">← Voltar para a Página Inicial</a>
        </div>
    </div>
</body>
</html>