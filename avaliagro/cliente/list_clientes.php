<?php
require_once '../classes/cliente.php';
require_once '../ini.php';
require_once '../includes/BD/consultas.php';
require_once '../html/menu.php';


$message ="";

//Exclusão de clientes
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    $id = (int)$_GET['id'];
    $acao = (int)$_GET['acao'];
    
    if($acao == 1){
        $excluir = new cliente();
        $message = $excluir->excluir_cliente($id, $conn);
        
    }
    
    // Verificar se o formulário foi enviado
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $cargo = $_POST['cliente'] ?? '';
        
    }
    
}

// Configuração de paginação
$limite = 10; // Número de itens por página
$pagina_atual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($pagina_atual - 1) * $limite;

// Contar o total de cargos
$total_resultados = $conn->query("SELECT COUNT(*) AS total FROM cliente");
$total_linhas = $total_resultados->fetch_assoc()['total'];

// Calcular o número total de páginas
$total_paginas = ceil($total_linhas / $limite);

// Buscar os cargos para a página atual
$result = $conn->query("$LIST_CLIENTES LIMIT $limite OFFSET $offset");

if (!$result) {
    die("Erro na consulta: " . $conn->error);
}


?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Clientes</title>
	<link rel="stylesheet" href="../css/estilo_tabelas.css">
	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
</head>
<body>

    <div class="table-container">
	<?php require_once '../html/menu.html';?>
        <h1 class="title">Lista de Clientes</h1>
        <?php if ($message): ?>
            <p class="message <?php echo strpos($message, 'Erro') !== false ? 'error' : ''; ?>">
                <?php echo htmlspecialchars($message); ?>
            </p>
        <?php endif; ?>

        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Cliente</th>
						<th>CNPJ</th>
						<th>Responsável</th>
                        <th><a href="inserir_cliente.php"?><img src="../imagens/icones/add.png" alt="Smiley face" width="25" height="25" style="float:left"></a></th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($cliente = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($cliente['nome']); ?></td>	
							<td><?php echo htmlspecialchars($cliente['cnpj']); ?></td>
							<td><?php echo htmlspecialchars($cliente['responsavel']); ?></td>						
							<td>
								<a href="editar_cliente.php?id=<?php echo htmlspecialchars($cliente['id']); ?>"><img src="../imagens/icones/editar.png" alt="Smiley face" width="15" height="15" style="float:left"></a>
                                <a href="list_clientes.php?id=<?php echo htmlspecialchars($cliente['id']); ?>&acao=1" onclick="return confirmarAcao()"><img src="../imagens/icones/delete.png" alt="Smiley face" width="15" height="15" style="float:left"></a>
                            </td>                           
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Nenhum usuário encontrado.</p>
        <?php endif; ?>

        <!-- Navegação de Paginação -->
        <div class="pagination">
            <?php if ($pagina_atual > 1): ?>
                <a href="?pagina=1">Primeira</a>
                <a href="?pagina=<?php echo $pagina_atual - 1; ?>">Anterior</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                <a href="?pagina=<?php echo $i; ?>" class="<?php echo $i === $pagina_atual ? 'active' : ''; ?>">
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>

            <?php if ($pagina_atual < $total_paginas): ?>
                <a href="?pagina=<?php echo $pagina_atual + 1; ?>">Próxima</a>
                <a href="?pagina=<?php echo $total_paginas; ?>">Última</a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

<?php
$conn->close();


?>