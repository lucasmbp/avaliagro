<?php
require_once '../classes/usuario.php';
require_once '../ini.php';
require_once '../includes/BD/consultas.php';
require_once '../html/menu.php';


// Verificar se o ID do usuário foi passado na URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID do usuário inválido ou não fornecido.");
}

$id = (int)$_GET['id'];

// Buscar os dados do usuário com segurança
$stmt = $conn->prepare("SELECT nome, email, cliente, cargo, area, perfil FROM usuario WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();
$stmt->close();

if (!$usuario) {
    die("Usuário não encontrado.");
}

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $cliente = $_POST['cliente'] ?? '';
    $cargo = $_POST['cargo'] ?? '';
    $area = $_POST['area'] ?? '';
    $perfil = $_POST['perfil'] ?? '';
    $senha = $_POST['senha'] ?? '';

    $user = new usuario();
    $mensagem = $user->editar_usuario($id, $nome, $senha, $email, $cliente, $cargo, $area, $perfil, $conn);

    echo "<script>alert('$mensagem'); window.location.href = 'index.php';</script>";
    exit();
}

// Buscar dados para preencher os selects (evitando SQL Injection)
$clientes = $conn->query("SELECT id, nome FROM cliente ORDER BY nome");
$cargos = $conn->query("SELECT id, cargo FROM cargo ORDER BY cargo");
$areas = $conn->query("SELECT id, area FROM area ORDER BY area");
$perfis = $conn->query("SELECT id, perfil FROM perfil ORDER BY perfil");

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
        <h2>Editar Usuário</h2>
        <form method="POST">
            <label for="nome">Nome</label>
            <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($usuario['nome']) ?>" required>

            <label for="email">E-mail</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($usuario['email']) ?>" required>

            <label for="senha">Senha (deixe em branco para não alterar)</label>
            <input type="password" id="senha" name="senha">

            <label for="cliente">Cliente:</label>
            <select id="cliente" name="cliente" required>
                <option value="">Selecione um cliente</option>
                <?php while ($cliente = $clientes->fetch_assoc()): ?>
                    <option value="<?= $cliente['id'] ?>" <?= ($cliente['id'] == $usuario['cliente']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cliente['nome']) ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label for="cargo">Cargo:</label>
            <select id="cargo" name="cargo" required>
                <option value="">Selecione um cargo</option>
                <?php while ($cargo = $cargos->fetch_assoc()): ?>
                    <option value="<?= $cargo['id'] ?>" <?= ($cargo['id'] == $usuario['cargo']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cargo['cargo']) ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label for="area">Área:</label>
            <select id="area" name="area" required>
                <option value="">Selecione uma área</option>
                <?php while ($area = $areas->fetch_assoc()): ?>
                    <option value="<?= $area['id'] ?>" <?= ($area['id'] == $usuario['area']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($area['area']) ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label for="perfil">Perfil:</label>
            <select id="perfil" name="perfil" required>
                <option value="">Selecione um perfil</option>
                <?php while ($perfil = $perfis->fetch_assoc()): ?>
                    <option value="<?= $perfil['id'] ?>" <?= ($perfil['id'] == $usuario['perfil']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($perfil['perfil']) ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <button type="submit">Atualizar</button>
        </form>
    </div>
</body>
</html>
