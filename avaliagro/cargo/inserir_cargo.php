<?php
require_once '../classes/cargo.php';
require_once '../ini.php';
require_once '../includes/BD/consultas.php';

session_start();
if (!isset($_SESSION['login'])) {
    header("Location: ../index.php");
    exit;
}

$message = "";

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cargo = $_POST['cargo'] ?? '';
    
    $position = new cargo();
    
    if(!($position->validar_cargo($cargo,$conn)))$message = "O cargo já existe";
    else{   $position->inserir_cargo($cargo, $conn); 
            $message = "Cargo cadastrado com sucesso";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inserir Cargo</title>
<link rel="stylesheet" href="../css/estilo.css">
</head>
<body>
    <div class="form-container">
        <h2>Inserir Cargo</h2>
        <form method="POST">
            <label for="cargo">Descrição do Cargo:</label>
            <input type="text" id="cargo" name="cargo" required>
            <button type="submit">Inserir</button>
        </form>
        <?php if ($message): ?>
            <p class="message <?php echo strpos($message, 'Erro') !== false ? 'error' : ''; ?>">
            <?php $redirectUrl ="list_cargo.php";?>
            <script>
    				alert("<?php echo htmlspecialchars($message); ?>");
           			window.location.href = "<?php echo $redirectUrl; ?>"
			</script>
			<?php //header("Location: list_cargo.php");?>
        <?php endif;?>
    </div>
</body>
</html>
