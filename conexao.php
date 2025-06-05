<?php
$host = 'localhost';  // Host do banco de dados
$user = 'root';       // Usuário do banco de dados
$senha = '';          // Senha do banco de dados (deixe vazia se não houver)
$banco = 'peti';      // Nome do banco de dados

$conn = new mysqli($host, $user, $senha, $banco);

if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}
?>
