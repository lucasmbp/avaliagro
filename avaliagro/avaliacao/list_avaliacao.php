<?php
require_once '../classes/cliente.php';
require_once '../ini.php';
require_once '../includes/BD/consultas.php';
require_once '../html/menu.php';

$message = "";

// Exclusão de clientes
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $acao = (int)$_GET['acao'];

    if ($acao == 1) {
        $excluir = new cliente();
        $message = $excluir->excluir_cliente($id, $conn);
    }
}

// Configuração de paginação
$limite = 10; // Número de itens por página
$pagina_atual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($pagina_atual - 1) * $limite;

// Contar o total de avaliações
$total_resultados = $conn->query("SELECT COUNT(*) AS total FROM avaliacao");
$total_linhas = $total_resultados->fetch_assoc()['total'];
$total_paginas = ceil($total_linhas / $limite);

// Buscar avaliações para a página atual
$result = $conn->query("SELECT DISTINCT(avaliacao_id) AS avaliacao_id, avaliado_nome, are.area AS area, car.cargo AS cargo 
                        FROM vw_minhas_avaliacoes vma
                        JOIN usuario usr ON usr.id = vma.avaliado_id
                        JOIN area are ON are.id = usr.area
                        JOIN cargo car ON car.id = usr.cargo 
                        WHERE avaliador_id = $cliente_sessao 
                        LIMIT $limite OFFSET $offset");

if (!$result) {
    die("Erro na consulta: " . $conn->error);
}

// Buscar competências disponíveis
$competencias = $conn->query("SELECT id, descricao FROM competencia where id not in(SELECT distinct competencia_id FROM resultado_avaliacao where avaliador_id = $id_sessao) limit 6;");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Avaliações</title>
    <link rel="stylesheet" href="../css/estilo_tabelas.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
</head>
<body>

    <div class="table-container">
        <?php require_once '../html/menu.html'; ?>
        <h1 class="title">Lista de Avaliações</h1>

        <?php if ($message): ?>
            <p class="message <?php echo strpos($message, 'Erro') !== false ? 'error' : ''; ?>">
                <?php echo htmlspecialchars($message); ?>
            </p>
        <?php endif; ?>

        <?php if ($result->num_rows > 0): ?>
            <form method="GET" action="avaliar.php">
                <label for="competencia">Selecione a Competência:</label>
                <select name="competencia_id" id="competencia" required>
                    <option value="">Selecione...</option>
                    <?php while ($comp = $competencias->fetch_assoc()): ?>
                        <option value="<?php echo $comp['id']; ?>">
                            <?php echo($comp['descricao']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
					<br>
					<br>
					<br>
					<br>
                <table>
                    <thead>
                        <tr>
                            <th>Avaliado</th>
                            <th>Setor</th>
                            <th>Cargo</th>
                            <th colspan="3" align="right">
                                <a href="inserir_avaliacao.php">
                                    <img src="../imagens/icones/add.png" alt="Adicionar" width="25" height="25" style="float:left">
                                </a>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($avaliacao = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($avaliacao['avaliado_nome']); ?></td>
                                <td><?php echo htmlspecialchars($avaliacao['area']); ?></td>
                                <td><?php echo htmlspecialchars($avaliacao['cargo']); ?></td>

                                <?php if ($perfil_sessao == $PERFIL_ADMIN || $perfil_sessao == $PERFIL_GESTOR): ?>
                                    <td>
                                        <a href="editar_cliente.php?id=<?php echo htmlspecialchars($avaliacao['avaliacao_id']); ?>">
                                            <img src="../imagens/icones/editar.png" alt="Editar" width="15" height="15" style="float:left">
                                        </a>
                                    </td>
                                    <td>
                                        <a href="list_clientes.php?id=<?php echo htmlspecialchars($avaliacao['avaliacao_id']); ?>&acao=1" onclick="return confirmarAcao()">
                                            <img src="../imagens/icones/delete.png" alt="Excluir" width="15" height="15" style="float:left">
                                        </a>
                                    </td>
                                <?php endif; ?>

                                <!-- Ícone de Avaliação com Envio de ID da Competência -->
                                <td>
                                    <button type="submit" name="avaliacao_id" value="<?php echo htmlspecialchars($avaliacao['avaliacao_id']); ?>" style="border: none; background: none; cursor: pointer;">
                                        <img src="../imagens/icones/avaliar.png" alt="Avaliar" width="15" height="15" style="float:left">
                                    </button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </form>
        <?php else: ?>
            <p>Nenhuma avaliação encontrada.</p>
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
