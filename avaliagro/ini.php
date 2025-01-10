<?php

// Configurações do banco de dados Teste 14
$host = 'localhost'; // Endereço do servidor do banco de dados
$username = 'root'; // Usuário do banco de dados
$password = ''; // Senha do banco de dados
$database = 'avaliagro'; // Nome do banco de dados
$PATH ="http://localhost/avaliagro/";

// Criando a conexão
$conn = new mysqli($host, $username, $password, $database);

// Verificando a conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

?>

