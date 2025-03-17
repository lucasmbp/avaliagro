<?php

// Configurações do banco de dados
$host = 'shuttle.proxy.rlwy.net'; // Endereço do servidor do banco de dados
$username = 'root'; // Usuário do banco de dados
$password = 'QpnJAoyEnmJgyttKXZQvOoeJAIzbAVjy'; // Senha do banco de dados
$database = 'railway'; // Nome do banco de dados
$port = '40137';
$socket = '';

$PATH ="http://localhost/avaliagro/";

// Criando a conexão
//$conn = new mysqli($host, $username, $password, $database);
$conn = new mysqli($host, $username, $password, $database, $port, $socket);

// Verificando a conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

?>

