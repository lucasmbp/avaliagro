<?php
require_once '../ini.php';
require_once '../includes/BD/consultas.php';
require_once '../classes/cargo.php';
require_once '../html/menu.php';

$message = "";

// Verifica se há solicitação para exclusão via GET
if (isset($_GET['id'], $_GET['acao']) && $_GET['acao'] == 1) {
    $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);

    if ($id) {
        $cargoObj = new cargo();
        $message = $cargoObj->excluir_cargo($id, $conn);
    } else {
        $message = "Erro: ID inválido para exclusão.";
    }
}

// Paginação
$limite = 50;
$pagina_atual = isset($_GET['pagina']) ? max(1, (int)$_GET['pagina']) : 1;
$offset = ($pagina_atual - 1) * $limite;

$total_resultados = $conn->query("SELECT COUNT(*) AS total FROM cargo");
$total_linhas = $total_resultados->fetch_assoc()['total'];
$total_paginas = ceil($total_linhas / $limite);

// Busca com ordenação e paginação (LIMIT e OFFSET devem ser usados na query $LIST_CARGOS)
$query = "$LIST_CARGOS ORDER BY ca.cargo LIMIT $limite OFFSET $offset";
$result = $conn->query($query);

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
    <link rel="stylesheet" href="https://cdn.materialdesignicons.com/7.2.96/css/materialdesignicons.min.css">
    <script>
        function confirmarAcao() {
            return confirm('Você tem certeza que deseja excluir este cargo?');
        }
    </script>
</head>
<body>
    <div class="table-container">
        <?php require_once '../html/menu.html'; ?>
        <h1 class="title">Lista de Cargos</h1>

        <?php if ($message): ?>
            <p class="message <?= strpos($message, 'Erro') !== false ? 'error' : ''; ?>">
                <?= htmlspecialchars($message); ?>
            </p>
        <?php endif; ?>

        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Descrição</th>
                        <th>
                            <a href="inserir_cargo.php">
                                <div class="icon" data-tooltip="Adicionar Cargo">
                                    <i class="mdi mdi-hospital"></i>
                                </div>
                            </a>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($cargo = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($cargo['cargo']); ?></td>
                            <td>
                                <a href="editar_cargo.php?id=<?= (int)$cargo['id']; ?>">
                                    <div data-tooltip="Editar">
                                        <i class="mdi mdi-pen"></i>
                                    </div>
                                </a>
                                <a href="list_cargo.php?id=<?= (int)$cargo['id']; ?>&acao=1" onclick="return confirmarAcao();">
                                    <div data-tooltip="Excluir">
                                        <i class="mdi mdi-delete"></i>
                                    </div>
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Nenhum cargo encontrado.</p>
        <?php endif; ?>

        <div class="pagination">
            <?php if ($pagina_atual > 1): ?>
                <a href="?pagina=1">Primeira</a>
                <a href="?pagina=<?= $pagina_atual - 1 ?>">Anterior</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                <a href="?pagina=<?= $i ?>" class="<?= $i === $pagina_atual ? 'active' : '' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>

            <?php if ($pagina_atual < $total_paginas): ?>
                <a href="?pagina=<?= $pagina_atual + 1 ?>">Próxima</a>
                <a href="?pagina=<?= $total_paginas ?>">Última</a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

<?php $conn->close(); ?>
