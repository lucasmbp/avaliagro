<?php
require_once '../ini.php';

$message = "";

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $descricao = $_POST['descricao'] ?? '';

    // Validar o campo descricao
    if (!empty($descricao)) {
        // Inserir o cargo no banco de dados
        $stmt = $conn->prepare("INSERT INTO cargo (descricao) VALUES (?)");
        $stmt->bind_param("s", $descricao);

        if ($stmt->execute()) {
            $message = "Cargo inserido com sucesso!";
			header("Location: list_cargo.php");
        } else {
            $message = "Erro ao inserir o cargo: " . $stmt->error;
        }

        $stmt->close();
        header("Location: index.php");
    } else {
        $message = "O campo descrição é obrigatório!";
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
            <label for="descricao">Descrição do Cargo:</label>
            <input type="text" id="descricao" name="descricao" required>
            <button type="submit">Inserir</button>
        </form>
        <?php if ($message): ?>
            <p class="message <?php echo strpos($message, 'Erro') !== false ? 'error' : ''; ?>">
                <?php echo htmlspecialchars($message); ?>
            </p>
        <?php endif; ?>
    </div>
</body>
</html>
