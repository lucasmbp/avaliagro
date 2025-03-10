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

<body>

    <button class="hamburger" onclick="toggleMenu()">☰</button>
    <div class="menu" id="menu">
        <a href="http://localhost/avaliagro/avaliagro/dashboard.php">Início</a>
        <a href="#sobre">Sobre</a>
        <a href="http://localhost/avaliagro/avaliagro/cargo/">Cargos</a>
        <a href="#contato">Contato</a>
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

