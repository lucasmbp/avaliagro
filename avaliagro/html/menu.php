<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: ../index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
	<link rel="stylesheet" href="../css/estilo_menu.css">
	 <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

<body>

    <button class="hamburger" onclick="toggleMenu()">☰</button>
    <div class="menu" id="menu">
        <a href="<?=$PATH?>avaliagro/dashboard.php">Início</a>
        <a href="<?=$PATH?>avaliagro/avaliacao/inserir_perguntas.php">Avaliações</a>
        <a href="<?=$PATH?>avaliagro/cargo/index.php">Cargos</a>
        <a href="<?=$PATH?>avaliagro/cliente/index.php">Clientes</a>
        <a href="<?=$PATH?>avaliagro/usuario/index.php">Usuários</a>
    </div>

    <script>
        function toggleMenu() {
            const menu = document.getElementById('menu');
            if (menu.style.display === 'flex') {
                menu.style.display = 'none';
            } else {
                menu.style.display = 'flex';
            }
        }
    </script>
    
    <br>
    <br>

