<?php
require_once '../ini.php';
require_once '../includes/BD/consultas.php';
require_once '../classes/area.php';
require_once '../html/menu.php';

$message = "";

// caso seja exclusão
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    $id = (int)$_GET['id'];
    $acao = (int)$_GET['acao'];
    
    // se a ação for 1 faz a exclusão do registro
    if($acao == 1){
        $excluir = new usuario();
        $message = $excluir->excluir_usuario($id, $conn);
        
    }
}

// Configuração de paginação
$limite = 10; // Número de itens por página
$pagina_atual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($pagina_atual - 1) * $limite;

// Contar o total de cargos
$total_resultados = $conn->query("SELECT COUNT(*) AS total FROM area");
$total_linhas = $total_resultados->fetch_assoc()['total'];

// Calcular o número total de páginas
$total_paginas = ceil($total_linhas / $limite);

// Buscar os cargos para a página atual
$result = $conn->query("$LIST_AREAS LIMIT $limite OFFSET $offset");

if (!$result) {
    die("Erro na consulta: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Áreas</title>
	<link rel="stylesheet" href="../css/estilo_tabelas.css">
</head>
<body>

    <div class="table-container">
        <h1 class="title">Lista de Áreas</h1>
         <?php if ($message): ?>
            <p class="message <?php echo strpos($message, 'Erro') !== false ? 'error' : ''; ?>">
                <?php echo htmlspecialchars($message); ?>
            </p>
        <?php endif; ?>
        
        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
						<th>Área</th>
						<th>Cliente</th>
                        <th><a href="inserir_area.php"?><img src="../imagens/icones/add.png" alt="Smiley face" width="25" height="25" style="float:left"></a></th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($area = $result->fetch_assoc()): ?>
                        <tr>
							<td><?php echo htmlspecialchars($area['area']); ?></td>
							<td><?php echo htmlspecialchars($area['cliente_nome']); ?></td>								
							<td>
								<a href="editar_area.php?id=<?php echo htmlspecialchars($area['id']); ?>"><img src="../imagens/icones/editar.png" alt="Smiley face" width="15" height="15" style="float:left"></a>
                                <a href="list_area.php?id=<?php echo htmlspecialchars($area['id']); ?>&acao=1""><img src="../imagens/icones/delete.png" alt="Smiley face" width="15" height="15" style="float:left"></a>
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
