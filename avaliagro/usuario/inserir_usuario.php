<?php
require_once '../ini.php';
require_once '../includes/BD/consultas.php';
require_once '../classes/usuario.php';

// Buscar dados para preencher as listas suspensas
$clientes = $conn->query($LIST_CLIENTES);
$cargos = $conn->query($LIST_CARGOS);
$areas = $conn->query($LIST_AREAS);
$perfis = $conn->query($LIST_PERFIS);

// Verificar se o formulário foi enviado
$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $login = $_POST['login'] ?? '';
    $senha = $_POST['senha'] ?? '';
    $email = $_POST['email'] ?? '';
    $cliente_id = $_POST['cliente'] ?? '';
    $cargo_id = $_POST['cargo'] ?? '';
    $area_id = $_POST['area'] ?? '';
    $perfil_id = $_POST['perfil'] ?? '';
    
    $user = new usuario();
    $user->inserir_usuario($nome, $login, $senha, $email, $cliente_id, $cargo_id, $area_id, $perfil_id);
   
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inserir Usuário</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .form-container {
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 400px;
            text-align: center;
        }
        input[type="text"], input[type="email"], input[type="password"], select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .message {
            margin-top: 10px;
            color: green;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Inserir Usuário</h2>
        <form method="POST">
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" required>

            <label for="login">Login:</label>
            <input type="text" id="login" name="login" required>

            <label for="senha">Senha:</label>
            <input type="password" id="senha" name="senha" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="cliente">Cliente:</label>
            <select id="cliente" name="cliente" required>
                <option value="">Selecione um cliente</option>
                <?php while ($cliente = $clientes->fetch_assoc()): ?>
                    <option value="<?php echo $cliente['id']; ?>">
                        <?php echo htmlspecialchars($cliente['nome']); ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label for="cargo">Cargo:</label>
            <select id="cargo" name="cargo" required>
                <option value="">Selecione um cargo</option>
                <?php while ($cargo = $cargos->fetch_assoc()): ?>
                    <option value="<?php echo $cargo['id']; ?>">
                        <?php echo htmlspecialchars($cargo['cargo']); ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label for="area">Área:</label>
            <select id="area" name="area" required>
                <option value="">Selecione uma área</option>
                <?php while ($area = $areas->fetch_assoc()): ?>
                    <option value="<?php echo $area['id']; ?>">
                        <?php echo htmlspecialchars($area['area']); ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label for="perfil">Perfil:</label>
            <select id="perfil" name="perfil" required>
                <option value="">Selecione um perfil</option>
                <?php while ($perfil = $perfis->fetch_assoc()): ?>
                    <option value="<?php echo $perfil['id']; ?>">
                        <?php echo htmlspecialchars($perfil['perfil']); ?>
                    </option>
                <?php endwhile; ?>
            </select>

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
