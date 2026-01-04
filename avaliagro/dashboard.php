<?php
require_once 'ini.php';
require_once 'includes/BD/consultas.php';


session_start();

if (!isset($_SESSION['login'])) {
    header("Location: index.php");
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tela Inicial</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
   	<link rel="stylesheet" href="css/dashboard.css">
</head>

<br>
<br>
    <body>
<?php if($perfil_sessao == $PERFIL_ADMIN):?>
        <div class="container">
            <div class="card">
                <div class="icon">
                	<span class="material-icons">gps_fixed</span>
                 </div>
                <a href="area/">Areas</a>
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
                <div class="icon">👤</div>
                <a href="usuario/">Usuários</a>
            </div>
            <div class="card">
                <div class="icon">🌍</div>
                <a href="logout.php">Sair</a>
            </div>
           </div>  
  <?php elseif ($perfil_sessao == $PERFIL_GESTOR):?>      
          <div class="container">
            <div class="card">
                <div class="icon">
    				<span class="material-icons">check_circle</span>
    			</div>
                <a href="avaliacao/">Avaliações</a>
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
  <?php elseif ($perfil_sessao == $PERFIL_AVALIADOR):?>
  		     <?php  
  		            header("Location: /avaliacao/index.php");
  		            exit;
  		   ?>
  <?php else:?>
  		   <?php  
  		            header("Location: /dashboard/index.php");
  		            exit;
  		   ?>
  <?php endif;?>

</body>
</html>
