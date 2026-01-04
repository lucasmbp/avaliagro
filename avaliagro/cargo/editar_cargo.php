<?php
require_once '../classes/cargo.php';
require_once '../ini.php';
require_once '../includes/BD/consultas.php';
require_once '../html/menu.php';

$message = "";
$retorno = null;
$result = null;

// Verifica se o ID foi passado e é válido
if (isset($_GET['id']) && filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    $id = (int)$_GET['id'];

    // Buscar os dados do cargo com segurança
    $stmt = $conn->prepare("$LIST_CARGOS WHERE ca.id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    // Se o formulário foi enviado
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $cargo = trim($_POST['cargo'] ?? '');

        if (!empty($cargo)) {
            $cargoObj = new cargo();
            $retorno = $cargoObj->editar_cargo($cargo, $id, $conn);
        } else {
            $message = "O campo cargo não pode estar vazio.";
        }
    }
} else {
    $message = "ID do cargo não fornecido ou inválido.";
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cargo</title>
    <link rel="stylesheet" href="../css/estilo_inserir.css">
</head>
<body>
    <div class="form-container">
        <h2>Editar Cargo</h2>

        <?php if (!empty($message)): ?>
            <p class="message"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <?php if (isset($retorno)): ?>
            <?php if ($retorno): ?>
                <script>
                    alert("Cargo atualizado com sucesso!");
                    window.location.href = "list_cargo.php";
                </script>
            <?php else: ?>
                <script>
                    alert("O cargo já existe!");
                    window.location.href = "editar_cargo.php?id=<?= $id ?>";
                </script>
            <?php endif; ?>
        <?php elseif ($result): ?>
            <form method="POST">
                <label for="cargo">Cargo</label>
                <input type="text" id="cargo" name="cargo" value="<?= htmlspecialchars($result['cargo']) ?>" required>
                <button type="submit">Atualizar</button>
            </form>
        <?php else: ?>
            <p class="message">Cargo não encontrado.</p>
        <?php endif; ?>
    </div>
</body>
</html>
