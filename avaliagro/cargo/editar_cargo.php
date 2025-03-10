<?php
require_once '../classes/cargo.php';
require_once '../ini.php';
require_once '../includes/BD/consultas.php';
require_once '../html/menu.php';

session_start();
if (!isset($_SESSION['login'])) {
    header("Location: ../index.php");
    exit;
}

// Verificar se o ID do usuario foi passado na URL
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    // Buscar os dados do usuario
    $stmt = $conn->prepare("$LIST_CARGOS WHERE ca.id = $id");
    $stmt->execute();
    $result = $stmt->get_result();
    $result = $result->fetch_assoc();
    $stmt->close();
    
    // Verificar se o formulário foi enviado
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $cargo = $_POST['cargo'] ?? '';
        
        $position = new cargo();
        $retorno = $position->editar_cargo($cargo, $id, $conn);
    }
    
} else {
    $message = "ID do usuario não fornecido.";
}


$conn->close();
?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cargo</title>
	<link rel="stylesheet" href="../css/estilo.css">
</head>
<body>
    <div class="form-container">
        <h2>Editar cargo</h2>
        <?php if (isset($retorno)): ?>
        	<?php if ($retorno): ?>
        		<?php $message = "Cargo atualizado!";
        		      $redirectUrl = "list_cargo.php";?>
        	<?php else: ?>
        		<?php $message = "O cargo já existe!";
        		      $redirectUrl = "editar_cargo.php?&id=$id"; ?>
        	<?php endif; ?>
        	<script>
            	alert("<?php echo htmlspecialchars($message); ?>");
           	 	window.location.href = "<?php echo $redirectUrl; ?>";
           </script>           
        <?php else: ?>
        	<form method="POST">
                <label for="nome">Cargo</label>
                <input type="text" id="cargo" name="cargo" value="<?php echo htmlspecialchars($result['cargo']); ?>" required>
                <button type="submit">Atualizar</button>
            </form>       	
        <?php endif; ?>
    </div>
</body>
</html>