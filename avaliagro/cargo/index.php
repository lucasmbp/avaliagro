<?php
// Configuração de conexão com o banco de dados
$conn = new mysqli('localhost', 'root', '', 'avaliagro');

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Configuração de paginação
$limite = 10; // Número de itens por página
$pagina_atual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($pagina_atual - 1) * $limite;

// Contar o total de cargos
$total_resultados = $conn->query("SELECT COUNT(*) AS total FROM cargo");
$total_linhas = $total_resultados->fetch_assoc()['total'];

// Calcular o número total de páginas
$total_paginas = ceil($total_linhas / $limite);

// Buscar os cargos para a página atual
$result = $conn->query("SELECT id, descricao FROM cargo LIMIT $limite OFFSET $offset");

if (!$result) {
    die("Erro na consulta: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Cargos</title>
	<link rel="stylesheet" href="../css/estilo.css">
</head>
<body>

    <div class="table-container">
	<?php require_once '../html/menu.html';?>
        <h1 class="title">Lista de Cargos</h1>
        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Descrição</th>
                        <th><a href="inserir_cargo.php"?><img src="../imagens/icones/add.png" alt="Smiley face" width="25" height="25" style="float:left"></a></th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($cargo = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($cargo['descricao']); ?></td>	
							<td>
								<a href="editar_cargo.php?id=<?php echo htmlspecialchars($cargo['id']); ?>"><img src="../imagens/icones/editar.png" alt="Smiley face" width="15" height="15" style="float:left"></a>
                                <a href="excluir_cargo.php?id=<?php echo htmlspecialchars($cargo['id']); ?>"><img src="../imagens/icones/delete.png" alt="Smiley face" width="15" height="15" style="float:left"></a>
                            </td>
                           
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Nenhum cargo encontrado.</p>
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