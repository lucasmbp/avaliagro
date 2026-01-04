<?php
require_once '../ini.php';
require_once '../includes/BD/consultas.php';
require_once '../classes/area.php';
require_once '../html/menu.php';

$message = "";

// Exclusão de área via GET com validação
if (isset($_GET['id'], $_GET['acao']) && $_GET['acao'] == 1) {
    $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);

    if ($id) {
        $areaObj = new area();
        $message = $areaObj->excluir_area($id, $conn);
    } else {
        $message = "Erro: ID inválido para exclusão.";
    }
}

// Paginação
$limite = 10;
$pagina_atual = isset($_GET['pagina']) ? max(1, (int)$_GET['pagina']) : 1;
$offset = ($pagina_atual - 1) * $limite;

// Total de registros
$total_resultados = $conn->query("SELECT COUNT(*) AS total FROM area");
$total_linhas = $total_resultados->fetch_assoc()['total'];
$total_paginas = ceil($total_linhas / $limite);

// Buscar áreas com limite e offset
$query = "$LIST_AREAS LIMIT ? OFFSET ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $limite, $offset);
$stmt->execute();
$result = $stmt->get_result();

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
    <link rel="stylesheet" href="https://cdn.materialdesignicons.com/7.2.96/css/materialdesignicons.min.css">
</head>
<body>
    <div class="table-container">
        <h1 class="title">Lista de Áreas</h1>

        <?php if ($message): ?>
            <p class="message <?= strpos($message, 'Erro') !== false ? 'error' : ''; ?>">
                <?= htmlspecialchars($message); ?>
            </p>
        <?php endif; ?>

        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Área</th>
                        <th>Cliente</th>
                        <th>
                            <a href="inserir_area.php">
                                <div class="icon" data-tooltip="Adicionar Área">
                                    <i class="mdi mdi-hospital"></i>
                                </div>
                            </a>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($area = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($area['area']) ?></td>
                            <td><?= htmlspecialchars($area['cliente_nome']) ?></td>
                            <td>
                                <a href="editar_area.php?id=<?= (int)$area['id']; ?>">
                                    <div data-tooltip="Editar">
                                        <i class="mdi mdi-pen"></i>
                                    </div>
                                </a>
                                <a href="list_area.php?id=<?= (int)$area['id']; ?>&acao=1" onclick="return confirm('Tem certeza que deseja excluir esta área?');">
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
            <p>Nenhuma área encontrada.</p>
        <?php endif; ?>

        <!-- Paginação -->
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
