<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: ../index.php");
    exit;
}
$id_sessao =  $_SESSION['id'];
$perfil_sessao =  $_SESSION['perfil'];
$cliente_sessao = $_SESSION['cliente'];
$nome_sessao = $_SESSION['nome'];


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
    	<?php if ($perfil_sessao == 1):?>
            <a href="<?=$PATH?>avaliagro/dashboard.php">Início</a>
            <a href="<?=$PATH?>avaliagro/avaliacao/inserir_perguntas.php">Avaliações</a>
            <a href="<?=$PATH?>avaliagro/cargo/index.php">Cargos</a>
            <a href="<?=$PATH?>avaliagro/cliente/index.php">Clientes</a>
            <a href="<?=$PATH?>avaliagro/usuario/index.php">Usuários</a>
        <?php elseif($perfil_sessao == 4):?>
        	<a href="<?=$PATH?>avaliagro/dashboard.php">Início</a>
            <a href="<?=$PATH?>avaliagro/avaliacao/index.php">Avaliações</a>
            <a href="<?=$PATH?>avaliagro/cargo/index.php">Cargos</a>
            <a href="<?=$PATH?>avaliagro/usuario/index.php">Usuários</a>           
        <?php elseif($perfil_sessao == 3): ?>
        	<a href="<?=$PATH?>avaliagro/avaliacao/index.php">Avaliações</a>       	
        <?php else:?>
        	<a href="<?=$PATH?>avaliagro/avaliacao/meu_dashboard.php">Meu Dasboard</a>
        <?php endif;?>
         	<a href="<?=$PATH?>avaliagro/logout.php">Sair</a>
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

