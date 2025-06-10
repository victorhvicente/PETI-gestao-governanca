<?php
include 'conexao.php'; // Ajuste o caminho conforme seu projeto

// Mensagem de status
$msg = '';
$msg_tipo = ''; // 'success' ou 'error'

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recebe dados do formulário
    $nome = trim($_POST['nome'] ?? '');
    $responsavel = trim($_POST['responsavel'] ?? '');
    $custo = floatval($_POST['custo'] ?? 0);
    $prazo = $_POST['prazo'] ?? '';

    $missoes = $_POST['missoes'] ?? [];
    $visoes = $_POST['visoes'] ?? [];
    $objetivo_ids = $_POST['objetivo_id'] ?? [];
    $objetivos_desc = $_POST['objetivos'] ?? [];

    if ($nome === '' || $responsavel === '' || $custo <= 0 || $prazo === '') {
        $msg = "Por favor, preencha todos os campos obrigatórios corretamente.";
        $msg_tipo = 'error';
    } else {
        // Inserir projeto
        $stmtProjeto = $conn->prepare("INSERT INTO projeto (nome, responsavel, custo, prazo) VALUES (?, ?, ?, ?)");
        $stmtProjeto->bind_param("ssds", $nome, $responsavel, $custo, $prazo);

        if ($stmtProjeto->execute()) {
            $projeto_id = $stmtProjeto->insert_id;

            // Missões
            $stmtMissao = $conn->prepare("INSERT INTO missao_projeto (projeto_id, missao) VALUES (?, ?)");
            foreach ($missoes as $missao) {
                $missao_trim = trim($missao);
                if ($missao_trim !== '') {
                    $stmtMissao->bind_param("is", $projeto_id, $missao_trim);
                    $stmtMissao->execute();
                }
            }
            $stmtMissao->close();

            // Visões
            $stmtVisao = $conn->prepare("INSERT INTO visao_projeto (projeto_id, visao) VALUES (?, ?)");
            foreach ($visoes as $visao) {
                $visao_trim = trim($visao);
                if ($visao_trim !== '') {
                    $stmtVisao->bind_param("is", $projeto_id, $visao_trim);
                    $stmtVisao->execute();
                }
            }
            $stmtVisao->close();

            // Objetivos
            $stmtObjetivo = $conn->prepare("INSERT INTO objetivo_projeto (projeto_id, objetivo_id, descricao) VALUES (?, ?, ?)");
            for ($i = 0; $i < count($objetivo_ids); $i++) {
                $id_obj = (int)$objetivo_ids[$i];
                $desc = trim($objetivos_desc[$i]);
                if ($desc !== '' && ($id_obj === 1 || $id_obj === 2)) { // valida id objetivo
                    $stmtObjetivo->bind_param("iis", $projeto_id, $id_obj, $desc);
                    $stmtObjetivo->execute();
                }
            }
            $stmtObjetivo->close();

            $msg = "Projeto cadastrado com sucesso!";
            $msg_tipo = 'success';

        } else {
            $msg = "Erro ao cadastrar projeto: " . $conn->error;
            $msg_tipo = 'error';
        }

        $stmtProjeto->close();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Cadastrar Projeto</title>
<style>
    body { font-family: Arial, sans-serif; background: #f9f9f9; padding: 20px;}
    .container { max-width: 700px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1);}
    h2 { margin-bottom: 20px; }
    .field-group { margin-bottom: 15px; }
    label { display: block; margin-bottom: 5px; font-weight: bold; }
    input[type="text"], input[type="number"], input[type="date"], select, textarea {
        width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;
        box-sizing: border-box; resize: vertical;
    }
    textarea { min-height: 60px; }
    .btn-small {
        padding: 5px 10px;
        margin-left: 5px;
        font-size: 16px;
        cursor: pointer;
    }
    .btn-small:disabled { cursor: not-allowed; opacity: 0.5; }
    input[type="submit"] {
        background-color: #28a745;
        border: none;
        color: white;
        padding: 12px 20px;
        cursor: pointer;
        border-radius: 4px;
        font-size: 16px;
        margin-top: 10px;
    }
    input[type="submit"]:hover { background-color: #218838; }
    .field-with-buttons { display: flex; align-items: flex-start; margin-bottom: 8px; }
    .field-with-buttons textarea, .field-with-buttons select {
        flex-grow: 1;
    }
    .field-with-buttons button {
        flex-shrink: 0;
        margin-left: 5px;
    }
    .message {
        padding: 10px;
        margin-bottom: 20px;
        border-radius: 5px;
    }
    .message.success { background-color: #d4edda; color: #155724; }
    .message.error { background-color: #f8d7da; color: #721c24; }
    .back-button-container { margin-top: 20px; }
    .back-button {
        text-decoration: none;
        color: #007bff;
        font-weight: bold;
    }
    .back-button:hover { text-decoration: underline; }
</style>
</head>
<body>

<div class="container">
    <h2>Cadastrar Projeto</h2>

    <?php if($msg): ?>
        <div class="message <?= $msg_tipo ?>"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>

    <form method="post" id="form-projeto">

        <div class="field-group">
            <label for="nome">Nome do Projeto:</label>
            <input type="text" id="nome" name="nome" required />
        </div>

        <div class="field-group">
            <label for="responsavel">Responsável pelo Projeto:</label>
            <input type="text" id="responsavel" name="responsavel" required />
        </div>

        <!-- Missões Dinâmicas -->
        <div class="field-group">
            <label>Missão(s):</label>
            <div id="container-missoes">
                <div class="field-with-buttons">
                    <textarea name="missoes[]" required></textarea>
                    <button type="button" class="btn-small" onclick="adicionarCampo('container-missoes')">+</button>
                    <button type="button" class="btn-small" onclick="removerCampo(this)" disabled>−</button>
                </div>
            </div>
        </div>

        <!-- Visões Dinâmicas -->
        <div class="field-group">
            <label>Visão(ões):</label>
            <div id="container-visoes">
                <div class="field-with-buttons">
                    <textarea name="visoes[]" required></textarea>
                    <button type="button" class="btn-small" onclick="adicionarCampo('container-visoes')">+</button>
                    <button type="button" class="btn-small" onclick="removerCampo(this)" disabled>−</button>
                </div>
            </div>
        </div>

        <!-- Objetivos Dinâmicos -->
        <div class="field-group">
            <label>Objetivo(s) do Projeto:</label>
            <div id="container-objetivos">
                <div class="field-with-buttons">
                    <select name="objetivo_id[]" required>
                        <option value="" disabled selected>Selecione o tipo</option>
                        <option value="1">Organizacional</option>
                        <option value="2">TI</option>
                    </select>
                    <textarea name="objetivos[]" placeholder="Descrição do objetivo" required></textarea>
                    <button type="button" class="btn-small" onclick="adicionarCampo('container-objetivos')">+</button>
                    <button type="button" class="btn-small" onclick="removerCampo(this)" disabled>−</button>
                </div>
            </div>
        </div>

        <div class="field-group">
            <label for="custo">Custo (R$):</label>
            <input type="number" id="custo" name="custo" step="0.01" min="0" required />
        </div>

        <div class="field-group">
            <label for="prazo">Prazo:</label>
            <input type="date" id="prazo" name="prazo" required />
        </div>

        <input type="submit" value="Cadastrar Projeto" />
    </form>

    <div class="back-button-container">
        <a href="index.php" class="back-button">← Voltar para a Página Inicial</a>
    </div>
</div>

<script>
function adicionarCampo(containerId) {
    const container = document.getElementById(containerId);
    const primeiroCampo = container.querySelector('.field-with-buttons');
    const novoCampo = primeiroCampo.cloneNode(true);

    // Limpa valores do novo campo
    const inputs = novoCampo.querySelectorAll('textarea, input, select');
    inputs.forEach(input => {
        if(input.tagName === 'SELECT') {
            input.selectedIndex = 0;
        } else {
            input.value = '';
        }
    });

    // Habilita botão remover no novo campo
    const botoes = novoCampo.querySelectorAll('.btn-small');
    botoes.forEach(botao => {
        if(botao.textContent === '−') botao.disabled = false;
    });

    container.appendChild(novoCampo);
    atualizarBotoes(container);
}

function removerCampo(botao) {
    const container = botao.closest('div[id^="container-"]');
    if(container.children.length > 1) {
        botao.closest('.field-with-buttons').remove();
    }
    atualizarBotoes(container);
}

function atualizarBotoes(container) {
    const campos = container.querySelectorAll('.field-with-buttons');
    campos.forEach((campo) => {
        const btnRemover = campo.querySelector('.btn-small:nth-child(3)');
        if(btnRemover) {
            btnRemover.disabled = (campos.length === 1);
        }
    });
}
</script>

</body>
</html>
