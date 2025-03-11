<?php

require_once '../ini.php';
require_once '../includes/BD/consultas.php';
require_once '../classes/cliente.php';
require_once '../html/menu.php';


// Buscar dados para preencher as listas suspensas
$clientes = $conn->query($LIST_CLIENTES);


// Verificar se o formulário foi enviado
$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $cnpj = $_POST['cnpj'] ?? '';
    $responsavel = $_POST['responsavel'] ?? '';
    
    $customer = new cliente();
    $customer->inserir_cliente($nome, $cnpj, $responsavel, $conn);
    
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inserir Usuário</title>
	<link rel="stylesheet" href="../css/estilo_inserir.css">
</head>
<body>
    <div class="form-container">
        <h2>Inserir Usuário</h2>
        <form method="POST">
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" required>

            <label for="cnpj">CNPJ:</label>
            <input type="text" id="cnpj" name="cnpj" required>

            <label for="responsavel">Responsável:</label>
            <input type="text" id="responsacvel" name="responsavel" required>
            <button type="submit">Inserir Usuário</button>
        </form>
        <?php if ($message): ?>
            <p class="message <?php echo strpos($message, 'Erro') !== false ? 'error' : ''; ?>">
                <?php echo htmlspecialchars($message); ?>
            </p>
        <?php endif; ?>
    </div>
</body>
</html>
