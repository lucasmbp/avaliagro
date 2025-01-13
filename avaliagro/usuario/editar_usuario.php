<?php
require_once '../classes/usuario.php';
require_once '../ini.php';
require_once '../includes/BD/consultas.php';


// Verificar se o ID do usuario foi passado na URL
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    // Verificar se o formulário foi enviado
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nome = $_POST['nome'] ?? '';
		$email = $_POST['email'] ?? '';
		$cliente = $_POST['cliente'] ?? '';
		$cargo = $_POST['cargo'] ?? '';
		$area = $_POST['area'] ?? '';
		$perfil = $_POST['perfil']?? '';
		
		$user = new usuario();
        $user->editar_usuario($nome, $email, $cliente, $cargo, $area, $perfil, $id, $conn);
         
    }
    
    // Buscar os dados do usuario
    $stmt = $conn->prepare("$LIST_USUARIOS WHERE u.id = $id");
    $stmt->execute();
    $result = $stmt->get_result();
    $usuario = $result->fetch_assoc();
    $stmt->close();
} else {
    $message = "ID do usuario não fornecido.";
}

// Buscar dados para preencher as listas suspensas
$clientes = $conn->query("$LIST_CLIENTES");
$cargos = $conn->query("$LIST_CARGOS");
$areas = $conn->query("$LIST_AREAS");
$perfis = $conn->query("$LIST_PERFIS");

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
        <?php if (isset($usuario)): ?>
            <form method="POST">
                <label for="nome">Nome</label>
                <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($usuario['nome']); ?>" required>
				
				<label for="email">E-mail</label>
				<input type="text" id="email" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" required>
				
				<label for="senha">Senha</label>
				<input type="password" id="senha" name="senha" value="" required>
				
				<label for="cliente">Cliente:</label>
					<select id="cliente" name="cliente" required>
						<option value="">Selecione um cliente</option>
						<?php while ($cliente = $clientes->fetch_assoc()): 
								if($cliente['id'] == $usuario['id_cliente'])?>
									<option value="<?php echo $cliente['id']; ?>" selected>
										<?php echo htmlspecialchars($cliente['nome']); ?>
									</option>
								<? endif;?>
						<?php endwhile; ?>
					</select>

				<label for="cargo">Cargo:</label>
					<select id="cargo" name="cargo" required>
						<option value="">Selecione um cargo</option>
						<?php while ($cargo = $cargos->fetch_assoc()): 
									if($cargo['id'] == $usuario['id_cargo'])?>								
										<option value="<?php echo $cargo['id']; ?>" selected>
											<?php echo htmlspecialchars($cargo['cargo']); ?>
										</option>										
									<? endif;?>
						<?php endwhile; ?>
					</select>

				<label for="area">Área:</label>
					<select id="area" name="area" required>
						<option value="">Selecione uma área</option>
						<?php while ($area = $areas->fetch_assoc()): 
								if($area['id'] == $usuario['id_area'])?>
									<option value="<?php echo $area['id']; ?>" selected>
										<?php echo htmlspecialchars($area['area']); ?>
									</option>
								<?endif;?>
						<?php endwhile; ?>
					</select>

				<label for="perfil">Perfil:</label>
					<select id="perfil" name="perfil" required>
						<option value="">Selecione um perfil</option>
						<?php while ($perfil = $perfis->fetch_assoc()): 
							if($perfil['id'] == $usuario['id_perfil'])?>
							<option value="<?php echo $perfil['id']; ?>" selected>
								<?php echo htmlspecialchars($perfil['perfil']); ?>
							</option>
							<?endif;?>
						<?php endwhile; ?>
					</select>
                <button type="submit">Atualizar</button>
            </form>
        <?php else: ?>
            <p>usuario não encontrado.</p>
        <?php endif; ?>
    </div>
</body>
</html>