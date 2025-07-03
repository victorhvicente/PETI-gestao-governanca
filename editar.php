<?php 
include 'conexao.php'; 
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Editar Item</title>
    <link rel="stylesheet" href="css/editar.css" />
</head>
<body>
    <div class="container">
        <h2>Editar Item</h2>

        <?php
        if (isset($_GET['tipo']) && isset($_GET['id'])) {
            $tipo = $_GET['tipo'];
            $id = (int)$_GET['id'];
            $data = null;
            $missoes = [];
            $visoes = [];
            $objetivosProjeto = [];

            // Busca os dados conforme o tipo
            if ($tipo == 'organizacao') {
                $result = $conn->query("SELECT * FROM organizacao WHERE id = $id");
                if ($result) $data = $result->fetch_assoc();

            } elseif ($tipo == 'objetivo') {
                $result = $conn->query("SELECT * FROM objetivo WHERE id = $id");
                if ($result) $data = $result->fetch_assoc();

            } elseif ($tipo == 'projeto') {
                // Busca dados do projeto
                $result = $conn->query("SELECT * FROM projeto WHERE id = $id");
                if ($result) $data = $result->fetch_assoc();

                if ($data) {
                    // Busca missões relacionadas
                    $missao_result = $conn->query("SELECT missao FROM missao_projeto WHERE projeto_id = $id");
                    while ($row = $missao_result->fetch_assoc()) {
                        $missoes[] = $row['missao'];
                    }

                    // Busca visões relacionadas
                    $visao_result = $conn->query("SELECT visao FROM visao_projeto WHERE projeto_id = $id");
                    while ($row = $visao_result->fetch_assoc()) {
                        $visoes[] = $row['visao'];
                    }

                    // Busca objetivos relacionados (tipo + descricao)
                    $sqlObjProj = "SELECT op.objetivo_id, op.descricao, o.tipo FROM objetivo_projeto op JOIN objetivo o ON op.objetivo_id = o.id WHERE op.projeto_id = $id";
                    $resObjProj = $conn->query($sqlObjProj);
                    while ($row = $resObjProj->fetch_assoc()) {
                        $objetivosProjeto[] = $row;
                    }
                }

            } else {
                echo "<p>Tipo inválido.</p>";
                exit;
            }

            if ($data) {

                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $id_post = (int)$_POST['id'];

                    if ($tipo == 'organizacao') {
                        $missao = $conn->real_escape_string($_POST['missao']);
                        $visao = $conn->real_escape_string($_POST['visao']);
                        $sql = "UPDATE organizacao SET missao = '$missao', visao = '$visao' WHERE id = $id_post";

                        if ($conn->query($sql) === TRUE) {
                            echo "<p>Dados atualizados com sucesso!</p>";
                        } else {
                            echo "<p>Erro ao atualizar: " . $conn->error . "</p>";
                        }

                    } elseif ($tipo == 'objetivo') {
                        $tipo_obj = $conn->real_escape_string($_POST['tipo']);
                        $descricao = $conn->real_escape_string($_POST['descricao']);
                        $sql = "UPDATE objetivo SET tipo = '$tipo_obj', descricao = '$descricao' WHERE id = $id_post";

                        if ($conn->query($sql) === TRUE) {
                            echo "<p>Dados atualizados com sucesso!</p>";
                        } else {
                            echo "<p>Erro ao atualizar: " . $conn->error . "</p>";
                        }

                    } elseif ($tipo == 'projeto') {
                        // Dados básicos do projeto
                        $nome = $conn->real_escape_string($_POST['nome']);
                        $responsavel = $conn->real_escape_string($_POST['responsavel']);
                        $custo = (float)$_POST['custo'];
                        $prazo = $conn->real_escape_string($_POST['prazo']);

                        // Atualiza dados principais do projeto
                        $sql = "UPDATE projeto SET nome = '$nome', responsavel = '$responsavel', custo = $custo, prazo = '$prazo' WHERE id = $id_post";

                        if ($conn->query($sql) === TRUE) {

                            // Atualiza missões
                            $conn->query("DELETE FROM missao_projeto WHERE projeto_id = $id_post");
                            if (isset($_POST['missao']) && is_array($_POST['missao'])) {
                                foreach ($_POST['missao'] as $missao_text) {
                                    $missao_text = $conn->real_escape_string(trim($missao_text));
                                    if ($missao_text !== '') {
                                        $conn->query("INSERT INTO missao_projeto (projeto_id, missao) VALUES ($id_post, '$missao_text')");
                                    }
                                }
                            }

                            // Atualiza visões
                            $conn->query("DELETE FROM visao_projeto WHERE projeto_id = $id_post");
                            if (isset($_POST['visao']) && is_array($_POST['visao'])) {
                                foreach ($_POST['visao'] as $visao_text) {
                                    $visao_text = $conn->real_escape_string(trim($visao_text));
                                    if ($visao_text !== '') {
                                        $conn->query("INSERT INTO visao_projeto (projeto_id, visao) VALUES ($id_post, '$visao_text')");
                                    }
                                }
                            }

                            // Atualiza objetivos
                            $conn->query("DELETE FROM objetivo_projeto WHERE projeto_id = $id_post");
                            if (isset($_POST['objetivo_id']) && isset($_POST['objetivo_descricao'])) {
                                $objetivo_ids_post = $_POST['objetivo_id'];
                                $objetivo_desc_post = $_POST['objetivo_descricao'];

                                for ($i = 0; $i < count($objetivo_ids_post); $i++) {
                                    $id_obj = (int)$objetivo_ids_post[$i];
                                    $desc = $conn->real_escape_string(trim($objetivo_desc_post[$i]));
                                    if ($desc !== '') {
                                        $conn->query("INSERT INTO objetivo_projeto (projeto_id, objetivo_id, descricao) VALUES ($id_post, $id_obj, '$desc')");
                                    }
                                }
                            }

                            echo "<p>Dados atualizados com sucesso!</p>";
                        } else {
                            echo "<p>Erro ao atualizar: " . $conn->error . "</p>";
                        }
                    }

                    // Recarregar dados após POST para mostrar no formulário
                    if ($tipo == 'projeto') {
                        $result = $conn->query("SELECT * FROM projeto WHERE id = $id_post");
                        $data = $result->fetch_assoc();

                        // Recarregar missões e visões
                        $missoes = [];
                        $visoes = [];
                        $objetivosProjeto = [];

                        $missao_result = $conn->query("SELECT missao FROM missao_projeto WHERE projeto_id = $id_post");
                        while ($row = $missao_result->fetch_assoc()) {
                            $missoes[] = $row['missao'];
                        }
                        $visao_result = $conn->query("SELECT visao FROM visao_projeto WHERE projeto_id = $id_post");
                        while ($row = $visao_result->fetch_assoc()) {
                            $visoes[] = $row['visao'];
                        }

                        $sqlObjProj = "SELECT op.objetivo_id, op.descricao, o.tipo FROM objetivo_projeto op JOIN objetivo o ON op.objetivo_id = o.id WHERE op.projeto_id = $id_post";
                        $resObjProj = $conn->query($sqlObjProj);
                        while ($row = $resObjProj->fetch_assoc()) {
                            $objetivosProjeto[] = $row;
                        }

                    } else {
                        $result = $conn->query("SELECT * FROM $tipo WHERE id = $id_post");
                        $data = $result->fetch_assoc();
                    }
                }

                // Formulário
                echo "<form method='post'>";
                echo "<input type='hidden' name='id' value='" . htmlspecialchars($data['id']) . "'>";

                if ($tipo == 'organizacao') {
                    echo "<label>Missão:</label><br>";
                    echo "<textarea name='missao' required>" . htmlspecialchars($data['missao']) . "</textarea><br>";
                    echo "<label>Visão:</label><br>";
                    echo "<textarea name='visao' required>" . htmlspecialchars($data['visao']) . "</textarea><br>";

                } elseif ($tipo == 'objetivo') {
                    echo "<label>Tipo de Objetivo:</label><br>";
                    echo "<select name='tipo' required>";
                    echo "<option value='organizacional'" . ($data['tipo'] == 'organizacional' ? ' selected' : '') . ">Organizacional</option>";
                    echo "<option value='ti'" . ($data['tipo'] == 'ti' ? ' selected' : '') . ">TI</option>";
                    echo "</select><br>";
                    echo "<label>Descrição:</label><br>";
                    echo "<textarea name='descricao' required>" . htmlspecialchars($data['descricao']) . "</textarea><br>";

                } elseif ($tipo == 'projeto') {
                    echo "<label>Nome:</label><br>";
                    echo "<input type='text' name='nome' value='" . htmlspecialchars($data['nome']) . "' required><br>";

                    echo "<label>Responsável:</label><br>";
                    echo "<input type='text' name='responsavel' value='" . htmlspecialchars($data['responsavel']) . "' required><br>";

                    echo "<label>Custo (R$):</label><br>";
                    echo "<input type='number' name='custo' value='" . htmlspecialchars($data['custo']) . "' step='0.01' required><br>";

                    echo "<label>Prazo:</label><br>";
                    echo "<input type='date' name='prazo' value='" . htmlspecialchars($data['prazo']) . "' required><br>";

                    // Missões (múltiplas)
                    echo "<label>Missão do Projeto:</label><br>";
                    if (!empty($missoes)) {
                        foreach ($missoes as $missao_text) {
                            echo "<textarea name='missao[]' required>" . htmlspecialchars($missao_text) . "</textarea><br>";
                        }
                    } else {
                        echo "<textarea name='missao[]' required></textarea><br>";
                    }
                    echo "<button type='button' onclick='adicionarCampoMissao()'>Adicionar Missão</button><br><br>";

                    // Visões (múltiplas)
                    echo "<label>Visão do Projeto:</label><br>";
                    if (!empty($visoes)) {
                        foreach ($visoes as $visao_text) {
                            echo "<textarea name='visao[]' required>" . htmlspecialchars($visao_text) . "</textarea><br>";
                        }
                    } else {
                        echo "<textarea name='visao[]' required></textarea><br>";
                    }
                    echo "<button type='button' onclick='adicionarCampoVisao()'>Adicionar Visão</button><br><br>";

                    // Objetivos (múltiplos: select tipo + descricao)
                    echo "<label>Objetivo(s) do Projeto:</label><br>";
                    echo "<div id='container-objetivos'>";
                    if (!empty($objetivosProjeto)) {
                        foreach ($objetivosProjeto as $obj) {
                            echo "<div class='field-objetivo'>";
                            echo "<select name='objetivo_id[]' required>";
                            echo "<option value='1'" . ($obj['objetivo_id'] == 1 ? ' selected' : '') . ">Organizacional</option>";
                            echo "<option value='2'" . ($obj['objetivo_id'] == 2 ? ' selected' : '') . ">TI</option>";
                            echo "</select> ";
                            echo "<textarea name='objetivo_descricao[]' required>" . htmlspecialchars($obj['descricao']) . "</textarea> ";
                            echo "<button type='button' onclick='removerObjetivo(this)'>−</button>";
                            echo "</div><br>";
                        }
                    } else {
                        echo "<div class='field-objetivo'>";
                        echo "<select name='objetivo_id[]' required>";
                        echo "<option value='' disabled selected>Selecione o tipo</option>";
                        echo "<option value='1'>Organizacional</option>";
                        echo "<option value='2'>TI</option>";
                        echo "</select> ";
                        echo "<textarea name='objetivo_descricao[]' required></textarea> ";
                        echo "<button type='button' onclick='removerObjetivo(this)' disabled>−</button>";
                        echo "</div><br>";
                    }
                    echo "</div>";
                    echo "<button type='button' onclick='adicionarCampoObjetivo()'>Adicionar Objetivo</button><br><br>";
                }

                echo "<input type='submit' value='Atualizar'>";
                echo "</form>";
            } else {
                echo "<p>Registro não encontrado.</p>";
            }
        } else {
            echo "<p>Parâmetros inválidos.</p>";
        }
        ?>

        <div class="back-button-container">
            <a href="listar_projetos.php" class="back-button">Voltar Para Projetos</a>
        </div>
    </div>

    <script>
    // JS para adicionar campos extras de missão e visão
    function adicionarCampoMissao() {
        const container = event.target.parentNode;
        const textarea = document.createElement('textarea');
        textarea.name = 'missao[]';
        textarea.required = true;
        container.insertBefore(textarea, event.target);
        container.insertBefore(document.createElement('br'), event.target);
    }

    function adicionarCampoVisao() {
        const container = event.target.parentNode;
        const textarea = document.createElement('textarea');
        textarea.name = 'visao[]';
        textarea.required = true;
        container.insertBefore(textarea, event.target);
        container.insertBefore(document.createElement('br'), event.target);
    }

    // JS para adicionar/remover objetivos
    function adicionarCampoObjetivo() {
        const container = document.getElementById('container-objetivos');
        const div = document.createElement('div');
        div.className = 'field-objetivo';

        div.innerHTML = `
            <select name="objetivo_id[]" required>
                <option value="" disabled selected>Selecione o tipo</option>
                <option value="1">Organizacional</option>
                <option value="2">TI</option>
            </select>
            <textarea name="objetivo_descricao[]" required></textarea>
            <button type="button" onclick="removerObjetivo(this)">−</button>
        `;

        container.appendChild(div);
        atualizarBotoesObjetivo();
    }

    function removerObjetivo(btn) {
        const container = document.getElementById('container-objetivos');
        if (container.children.length > 1) {
            btn.parentElement.remove();
        }
        atualizarBotoesObjetivo();
    }

    function atualizarBotoesObjetivo() {
        const container = document.getElementById('container-objetivos');
        const botoes = container.querySelectorAll('button');
        if (botoes.length === 1) {
            botoes[0].disabled = true;
        } else {
            botoes.forEach(btn => btn.disabled = false);
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        atualizarBotoesObjetivo();
    });
    </script>

</body>
</html>
