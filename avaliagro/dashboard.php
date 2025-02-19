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
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            max-width: 1200px;
            justify-content: center;
        }
        .card {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
            width: 200px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .card:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 12px rgba(0, 0, 0, 0.2);
        }
        .card a {
            text-decoration: none;
            color: #007BFF;
            font-size: 1.2em;
            font-weight: bold;
        }
        .card a:hover {
            color: #0056b3;
        }
        .card .icon {
            font-size: 3em;
            color: #007BFF;
            margin-bottom: 10px;
        }
    </style>
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
            <a href="avaliacoes.php">Avaliações</a>
        </div>
    </div>
</body>
</html>
