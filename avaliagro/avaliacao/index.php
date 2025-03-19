<?php
require_once '../ini.php';
require_once '../includes/BD/consultas.php';
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tela Inicial</title>
    <link rel="stylesheet" href="../css/dashboard.css">
</head>

<body>
    <div class="container">
        <div class="card">
            <div class="icon">📦</div>
            <a href="inserir_avaliacao.php">Criar avaliação</a>
        </div>
        <div class="card">
            <div class="icon">👤</div>
            <a href="usuario/">Listar avaliações</a>
        </div>
        <div class="card">
            <div class="icon">🏢</div>
            <a href="cliente/">Avaliar</a>
        </div>
        <div class="card">
            <div class="icon">🛠️</div>
            <a href="perfis.php">Dashboard de apuração</a>
        </div>
    </div>
</body>
</html>
