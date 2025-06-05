<?php include 'conexao.php'; ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Item</title>

    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h2>Editar Item</h2>
        
        <?php
        // Verificar se o tipo e o id foram passados
        if (isset($_GET['tipo']) && isset($_GET['id'])) {
            $tipo = $_GET['tipo'];
            $id = $_GET['id'];
            $table = '';
            $data = null;

            // Buscar os dados com base no tipo
            if ($tipo == 'organizacao') {
                $result = $conn->query("SELECT * FROM organizacao WHERE id = $id");
                $table = 'organizacao';
            } elseif ($tipo == 'objetivo') {
                $result = $conn->query("SELECT * FROM objetivo WHERE id = $id");
                $table = 'objetivo';
            } elseif ($tipo == 'projeto') {
                $result = $conn->query("SELECT * FROM projeto WHERE id = $id");
                $table = 'projeto';
            }

            if ($result && $row = $result->fetch_assoc()) {
                $data = $row;
                
                // Exibir o formulário com os dados
                echo "<form method='post'>
                        <input type='hidden' name='id' value='{$id}'>";

                if ($tipo == 'organizacao') {
                    echo "<label>Missão:</label><br>
                          <textarea name='missao' required>{$data['missao']}</textarea><br>
                          <label>Visão:</label><br>
                          <textarea name='visao' required>{$data['visao']}</textarea><br>";
                } elseif ($tipo == 'objetivo') {
                    echo "<label>Tipo de Objetivo:</label><br>
                          <select name='tipo'>
                              <option value='organizacional' " . ($data['tipo'] == 'organizacional' ? 'selected' : '') . ">Organizacional</option>
                              <option value='ti' " . ($data['tipo'] == 'ti' ? 'selected' : '') . ">TI</option>
                          </select><br>
                          <label>Descrição:</label><br>
                          <textarea name='descricao' required>{$data['descricao']}</textarea><br>";
                } elseif ($tipo == 'projeto') {
                    // Adicionar campos para o projeto
                    echo "<label>Nome:</label><br>
                          <input type='text' name='nome' value='{$data['nome']}' required><br>
                          <label>Objetivo:</label><br>
                          <select name='objetivo_id' required>";

                    // Populando os objetivos
                    $objetivos = $conn->query("SELECT * FROM objetivo");
                    while ($obj = $objetivos->fetch_assoc()) {
                        echo "<option value='{$obj['id']}'" . ($data['objetivo_id'] == $obj['id'] ? ' selected' : '') . ">{$obj['descricao']}</option>";
                    }

                    echo "</select><br>
                          <label>Responsável:</label><br>
                          <input type='text' name='responsavl' value='{$data['responsavl']}' required><br>
                          <label>Custo (R$):</label><br>
                          <input type='number' name='custo' value='{$data['custo']}' step='0.01' required><br>
                          <label>Prazo:</label><br>
                          <input type='date' name='prazo' value='{$data['prazo']}' required><br>";
                }

                echo "<input type='submit' value='Atualizar'></form>";

                // Atualizar os dados no banco de dados
                if ($_POST) {
                    $id = $_POST['id'];

                    if ($tipo == 'organizacao') {
                        $missao = $_POST['missao'];
                        $visao = $_POST['visao'];
                        $conn->query("UPDATE organizacao SET missao = '$missao', visao = '$visao' WHERE id = $id");
                    } elseif ($tipo == 'objetivo') {
                        $descricao = $_POST['descricao'];
                        $tipo_obj = $_POST['tipo'];
                        $conn->query("UPDATE objetivo SET tipo = '$tipo_obj', descricao = '$descricao' WHERE id = $id");
                    } elseif ($tipo == 'projeto') {
                        $nome = $_POST['nome'];
                        $objetivo_id = $_POST['objetivo_id'];
                        $responsavl = $_POST['responsavl'];
                        $custo = $_POST['custo'];
                        $prazo = $_POST['prazo'];
                        $conn->query("UPDATE projeto SET nome = '$nome', objetivo_id = $objetivo_id, responsavl = '$responsavl', custo = $custo, prazo = '$prazo' WHERE id = $id");
                    }

                    echo "<p>Dados atualizados com sucesso!</p>";
                }
            }
        }
        ?>

        <div class="back-button-container">
            <a href="index.php" class="back-button">Voltar para a Página Inicial</a>
        </div>
    </div>
</body>
</html>
