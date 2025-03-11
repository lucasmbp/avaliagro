<?php
require_once '../classes/area.php';
require_once '../ini.php';
require_once '../includes/BD/consultas.php';
require_once '../html/menu.php';

$message = "";
$clientes = $conn->query($LIST_CLIENTES);

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $area = $_POST['area'] ?? '';
    $cliente = $_POST['cliente'] ?? '';
    
    //cria objeto área
    $obj = new area();
    $message = $obj->inserir_area($area, $cliente, $conn);  
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inserir Área</title>
<link rel="stylesheet" href="../css/estilo.css">
</head>
<body>
    <div class="form-container">
        <h2>Inserir Área</h2>
        <form method="POST">
            <label for="cargo">Descrição da Área:</label>
            	<input type="text" id="area" name="area" required>
            <label for="cliente">Cliente:</label>
                <select id="cliente" name="cliente" required>
                    <option value="">Selecione um cliente</option>
                    <?php while ($cliente = $clientes->fetch_assoc()): ?>
                        <option value="<?php echo $cliente['id']; ?>">
                            <?php echo htmlspecialchars($cliente['nome']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            <button type="submit">Inserir</button>
        </form>
           <?php if ($message): ?>
            <p class="message <?php echo strpos($message, 'Erro') !== false ? 'error' : ''; ?>">
            <?php $redirectUrl ="list_area.php";?>
            <script>
    				alert("<?php echo htmlspecialchars($message); ?>");
           			window.location.href = "<?php echo $redirectUrl; ?>"
			</script>
			<?php //header("Location: list_cargo.php");?>
        <?php endif;?>
    </div>
</body>
</html>