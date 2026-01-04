<?php
require_once '../classes/area.php';
require_once '../ini.php';
require_once '../includes/BD/consultas.php';
require_once '../html/menu.php';

$message = "";

// Buscar clientes disponíveis
$clientes = $conn->query($LIST_CLIENTES);

// Processar formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $area = trim($_POST['area'] ?? '');
    $cliente = $_POST['cliente'] ?? '';

    if (empty($area) || empty($cliente)) {
        $message = "Erro: Todos os campos são obrigatórios.";
    } else {
        $obj = new area();
        $message = $obj->inserir_area($area, $cliente, $conn);
        
        // Redirecionamento após inserção com mensagem de sucesso/erro
        echo "<script>
                alert('".addslashes($message)."');
                window.location.href = 'list_area.php';
              </script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inserir Área</title>
    <link rel="stylesheet" href="../css/estilo_inserir.css">
</head>
<body>
    <div class="form-container">
        <h2>Inserir Área</h2>
        <form method="POST">
            <label for="area">Descrição da Área:</label>
            <input type="text" id="area" name="area" required>

            <label for="cliente">Cliente:</label>
            <select id="cliente" name="cliente" required>
                <option value="">Selecione um cliente</option>
                <?php while ($cliente = $clientes->fetch_assoc()): ?>
                    <option value="<?= $cliente['id']; ?>">
                        <?= htmlspecialchars($cliente['nome']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <button type="submit">Inserir</button>
        </form>

        <?php if (!empty($message) && strpos($message, 'Erro') !== false): ?>
            <p class="message error"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
