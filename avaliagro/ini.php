<?php

// Configurações do banco de dados
$host = 'localhost'; // Endereço do servidor do banco de dados
$username = 'root'; // Usuário do banco de dados
$password = ''; // Senha do banco de dados
$database = 'avaliagro'; // Nome do banco de dados
$port = '3306';
$socket = '';

$PATH ="http://localhost/avaliagro/";
$PERFIL_ADMIN = 1;
$PERFIL_GESTOR = 4;
$PERFIL_AVALIADO = 2;
$PERFIL_AVALIADOR = 3;

// Criando a conexão
//$conn = new mysqli($host, $username, $password, $database);
$conn = new mysqli($host, $username, $password, $database, $port, $socket);

// Verificando a conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

?>

