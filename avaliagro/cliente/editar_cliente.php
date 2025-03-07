<?php
require_once '../classes/cliente.php';
require_once '../ini.php';
require_once '../includes/BD/consultas.php';
require_once '../html/menu.html';

session_start();
if (!isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}


// Verificar se o ID do usuario foi passado na URL
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    // Verificar se o formulário foi enviado
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nome = $_POST['nome'] ?? '';
        $cnpj = $_POST['cnpj'] ?? '';
        $responsavel = $_POST['responsavel'] ?? '';
        
        $customer = new cliente();
        $customer->editar_cliente($nome, $cnpj, $responsavel, $id, $conn);
        
    }
    
    // Buscar os dados do usuario
    $stmt = $conn->prepare("$LIST_CLIENTES WHERE cl.id = $id");
    $stmt->execute();
    $result = $stmt->get_result();
    $cliente = $result->fetch_assoc();
    $stmt->close();
} else {
    $message = "ID do usuario não fornecido.";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuário</title>
	<link rel="stylesheet" href="../css/estilo.css">
</head>
<body>
    <div class="form-container">
        <h2>Editar Usuário</h2>
        <?php if (isset($cliente)): ?>
            <form method="POST">
                <label for="nome">Nome</label>
                <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($cliente['nome']); ?>" required>
				
				<label for="cnpj">CNPJ</label>
				<input type="text" id="cnpj" name="cnpj" value="<?php echo htmlspecialchars($cliente['cnpj']); ?>" required>
				
				<label for="responsavel">Responsável</label>
				<input type="text" id="responsavel" name="responsavel" value="<?php echo htmlspecialchars($cliente['responsavel']); ?>" required>
			
                <button type="submit">Atualizar</button>
            </form>
        <?php else: ?>
            <p>usuario não encontrado.</p>
        <?php endif; ?>
    </div>
</body>
</html>