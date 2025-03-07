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
    /* Reset básico e configurações globais */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: 'Roboto', 'Segoe UI', Arial, sans-serif;
  background-color: #f8f9fa;
  color: #333;
  line-height: 1.6;
  padding: 20px;
  min-height: 100vh;
}

/* Container principal */
.container {
  max-width: 1200px;
  margin: 0 auto;
  display: grid;
  grid-template-columns: 1fr;
  gap: 16px;
  padding: 16px;
}

/* Estilo dos cards */
.card {
  background-color: white;
  border-radius: 12px;
  padding: 20px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  display: flex;
  align-items: center;
  cursor: pointer;
}

.card:hover {
  transform: translateY(-5px);
  box-shadow: 0 6px 14px rgba(0, 0, 0, 0.1);
}

/* Ícones */
.icon {
  font-size: 24px;
  margin-right: 15px;
  display: flex;
  align-items: center;
  justify-content: center;
  width: 48px;
  height: 48px;
  background-color: #f0f3f9;
  border-radius: 50%;
}

/* Links */
.card a {
  text-decoration: none;
  color: #333;
  font-weight: 500;
  font-size: 16px;
  flex-grow: 1;
  padding: 10px 0;
}

/* Media queries para tablet */
@media (min-width: 600px) {
  .container {
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
  }
}

/* Media queries para desktop */
@media (min-width: 992px) {
  .container {
    grid-template-columns: repeat(3, 1fr);
    gap: 24px;
  }
  
  .card {
    padding: 24px;
  }
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
