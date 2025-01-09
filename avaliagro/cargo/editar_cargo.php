<?php
require_once '../ini.php';

// Verificar se o ID do cargo foi passado na URL
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    // Verificar se o formulário foi enviado
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $descricao = $_POST['descricao'] ?? '';

        // Validar o campo descricao
        if (!empty($descricao)) {
            // Atualizar o cargo no banco de dados
            $stmt = $conn->prepare("UPDATE cargo SET descricao = ? WHERE id = ?");
            $stmt->bind_param("si", $descricao, $id);

            if ($stmt->execute()) {
                $message = "Cargo atualizado com sucesso!";
            } else {
                $message = "Erro ao atualizar o cargo: " . $stmt->error;
            }
            
            $stmt->close();          
			header("Location: index.php");
        } else {
            $message = "O campo descrição é obrigatório!";
        }
    }

    // Buscar os dados do cargo
    $stmt = $conn->prepare("SELECT descricao FROM cargo WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $cargo = $result->fetch_assoc();
    $stmt->close();
} else {
    $message = "ID do cargo não fornecido.";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cargo</title>
	<link rel="stylesheet" href="../css/estilo.css">
</head>
<body>
    <div class="form-container">
        <h2>Editar Cargo</h2>
        <?php if (isset($cargo)): ?>
            <form method="POST">
                <label for="descricao">Descrição do Cargo:</label>
                <input type="text" id="descricao" name="descricao" value="<?php echo htmlspecialchars($cargo['descricao']); ?>" required>
                <button type="submit">Atualizar</button>
            </form>
        <?php else: ?>
            <p>Cargo não encontrado.</p>
        <?php endif; ?>
    </div>
</body>
</html>