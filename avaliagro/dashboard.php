<?php
require_once 'ini.php';
require_once 'includes/BD/consultas.php';
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
   	<link rel="stylesheet" href="css/dashboard.css">
</head>

<body>
    <div class="container">
        <div class="card">
            <div class="icon">📦</div>
            <a href="cargo/">Itens</a>
        </div>
        <div class="card">
            <div class="icon">👤</div>
            <a href="usuario/">Usuário</a>
        </div>
        <div class="card">
            <div class="icon">🏢</div>
            <a href="cliente/">Cliente</a>
        </div>
        <div class="card">
            <div class="icon">🛠️</div>
            <a href="perfis.php">Perfil</a>
        </div>
        <div class="card">
            <div class="icon">🌍</div>
            <a href="logout.php">Sair</a>
        </div>
        <div class="card">
            <div class="icon">✅</div>
            <a href="avaliacao/">Avaliações</a>
        </div>
    </div>
</body>
</html>
