<?php
require_once '../classes/cargo.php';
require_once '../ini.php';
require_once '../includes/BD/consultas.php';
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}

$message = "";
   

// Verificar se o ID do cargo foi passado na URL
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    $id = (int)$_GET['id'];
    $acao = (int)$_GET['acao'];
    
    if($acao == 1){
        $excluir = new cargo();
       $message = $excluir->excluir_cargo($id, $conn);
        
    }
    
    // Verificar se o formulário foi enviado
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $cargo = $_POST['cargo'] ?? '';
        
    }

}
// ------------------Configuração de paginação----------------------------
$limite = 50; // Número de itens por página
$pagina_atual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($pagina_atual - 1) * $limite;

// Contar o total de cargos
$total_resultados = $conn->query("SELECT COUNT(*) AS total FROM cargo");
$total_linhas = $total_resultados->fetch_assoc()['total'];

// Calcular o número total de páginas
$total_paginas = ceil($total_linhas / $limite);

// Buscar os cargos para a página atual
$result = $conn->query("$LIST_CARGOS order by ca.cargo");

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
	<link rel="stylesheet" href="../css/estilo_tabelas.css">
	
	 <script>
        function confirmarAcao() {
            return confirm('Você tem certeza que deseja continuar?');
        }
    </script>
    
</head>
<body>

    <div class="table-container">
	<?php require_once '../html/menu.html';?>
        <h1 class="title">Lista de Cargos</h1>
        
         <?php if ($message): ?>
            <p class="message <?php echo strpos($message, 'Erro') !== false ? 'error' : ''; ?>">
                <?php echo htmlspecialchars($message); ?>
            </p>
        <?php endif; ?>
        
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
                            <td><?php echo htmlspecialchars($cargo['cargo']); ?></td>	
							<td>
								<a href="editar_cargo.php?id=<?php echo htmlspecialchars($cargo['id']); ?>"><img src="../imagens/icones/editar.png" alt="Smiley face" width="15" height="15" style="float:left"></a>
                                <a href="list_cargo.php?id=<?php echo htmlspecialchars($cargo['id']); ?>&acao=1"  onclick="return confirmarAcao()"><img src="../imagens/icones/delete.png" alt="Smiley face" width="15" height="15" style="float:left"></a>
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