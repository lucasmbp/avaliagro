<?php
require_once '../classes/area.php';
require_once '../ini.php';
require_once '../includes/BD/consultas.php';
require_once '../html/menu.php';


// Verificar se o ID do usuário foi passado na URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID do usuário inválido ou não fornecido.");
}

$id = (int)$_GET['id'];

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cliente = $_POST['cliente'] ?? '';
    $area = $_POST['area'] ?? '';
    
    $obj = new area();
    $mensagem = $obj->editar_area($area, $id, $cliente, $conn);
    
    echo "<script>alert('$mensagem'); window.location.href = 'index.php';</script>";
    exit();
}

// Buscar dados para preencher os selects (evitando SQL Injection)
$clientes = $conn->query("SELECT id, nome FROM cliente ORDER BY nome");
//$areas = $conn->query("SELECT * FROM area where id = $id");
$stmt = $conn->prepare("$LIST_AREAS WHERE a.id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$areas = $result->fetch_assoc();


$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuário</title>
    <link rel="stylesheet" href="../css/estilo_inserir.css">
</head>
<body>
    <div class="form-container">
        <h2>Editar Área</h2>
        <form method="POST">
            <label for="nome">Área</label>
            <input type="text" id="area" name="area" value="<?= htmlspecialchars($areas['area']) ?>" required>
            
            <label for="cliente">Cliente:</label>
            <select id="cliente" name="cliente" required>
                <option value="">Selecione um cliente</option>
                <?php while ($cliente = $clientes->fetch_assoc()): ?>
                    <option value="<?= $cliente['id'] ?>" <?= ($cliente['id'] == $areas['cliente_id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cliente['nome']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <button type="submit">Atualizar</button>
        </form>
    </div>
</body>
</html>
