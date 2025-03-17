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
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
   	<link rel="stylesheet" href="css/dashboard.css">
</head>

<body>
    <div class="container">
        <div class="card">
            <div class="icon">
            	<span class="material-icons">gps_fixed</span>
             </div>
            <a href="area/">Areas</a>
        </div>
        <div class="card">
            <div class="icon">
            	<span class="material-icons">group</span>
            </div>
            <a href="avaliados/">Avaliados</a>
        </div>
                <div class="card">
            <div class="icon">
				<span class="material-icons">check_circle</span>
			</div>
            <a href="avaliacao/">Avaliações</a>
        </div>
        <div class="card">
        	<div class="icon">
        		<span class="material-icons">work</span>
        	</div>
            <a href="cargo/">Cargos</a>
        </div>
        <div class="card">
            <div class="icon">
            	<span class="material-icons">business</span>
			</div>
            <a href="cliente/">Clientes</a>
        </div>
        <div class="card">       
            <div class="icon">
            	<span class="material-icons">vpn_key</span>
            </div>
            <a href="perfil/">Perfis</a>
        </div>
        <div class="card">
            <div class="icon">👤</div>
            <a href="usuario/">Usuários</a>
        </div>
        <div class="card">
            <div class="icon">🌍</div>
            <a href="logout.php">Sair</a>
        </div>
        
    </div>
</body>
</html>
