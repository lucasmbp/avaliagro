<?php
require_once '../classes/area.php';
require_once '../ini.php';
require_once '../includes/BD/consultas.php';
require_once '../html/menu.php';

// Validar o ID do usuário
if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    die("ID do usuário inválido ou não fornecido.");
}

$id = (int)$_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cliente = $_POST['cliente'] ?? '';
    $area = $_POST['area'] ?? '';

    // Validação básica
    if (empty($cliente) || empty($area)) {
        echo "<script>alert('Todos os campos são obrigatórios.'); history.back();</script>";
        exit();
    }

    $obj = new area();
    $mensagem = $obj->editar_area($area, $id, $cliente, $conn);

    echo "<script>alert('".addslashes($mensagem)."'); window.location.href = 'index.php';</script>";
    exit();
}

// Buscar clientes para o select
$clientes = $conn->query("SELECT id, nome FROM cliente ORDER BY nome");

// Buscar dados da área pelo ID usando prepared statement
$stmt = $conn->prepare("$LIST_AREAS WHERE a.id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$areaData = $result->fetch_assoc();

if (!$areaData) {
    die("Área não encontrada.");
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Área</title>
    <link rel="stylesheet" href="../css/estilo_inserir.css">
</head>
<body>
    <div class="form-container">
        <h2>Editar Área</h2>
        <form method="POST">
            <label for="area">Área</label>
            <input type="text" id="area" name="area" value="<?= htmlspecialchars($areaData['area']) ?>" required>
            
            <label for="cliente">Cliente:</label>
            <select id="cliente" name="cliente" required>
                <option value="">Selecione um cliente</option>
                <?php while ($cliente = $clientes->fetch_assoc()): ?>
                    <option value="<?= $cliente['id'] ?>" <?= ($cliente['id'] == $areaData['cliente_id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cliente['nome']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <button type="submit">Atualizar</button>
        </form>
    </div>
</body>
</html>
