<?php
require_once '../ini.php';
require_once '../includes/BD/consultas.php';
require_once '../html/menu.php';

$message = "";

// Validação da avaliação
$avaliacao_id = isset($_GET['avaliacao_id']) ? (int)$_GET['avaliacao_id'] : 0;
$perguntas = $conn->query("$LIST_PERGUNTA WHERE per.avaliacao = $avaliacao_id");
$dados_avaliado = $conn->query("SELECT ava.nome as avaliado, car.cargo as cargo, are.area as area
                                FROM avaliacao ava
                                join usuario usu on usu.id = ava.avaliado
                                join cargo car on car.id = usu.cargo
                                join area are on are.id = usu.area where ava.id = $avaliacao_id");
$avaliado = $dados_avaliado->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avaliação</title>
    <link rel="stylesheet" href="../css/estilo_avaliacao.css">
</head>
<body>

<div class="table-container">
    <h2 class="title">Avaliação de Desempenho</h2>
<div class="info-box">
        <label><strong>Avaliado:</strong> <?php echo htmlspecialchars($avaliado['avaliado']); ?></label><br>
        <label><strong>Setor:</strong> <?php echo htmlspecialchars($avaliado['area']); ?></label><br>
        <label><strong>Cargo:</strong> <?php echo htmlspecialchars($avaliado['cargo']); ?></label><br>
</div>
    <?php if ($message): ?>
        <p class="message <?php echo strpos($message, 'Erro') !== false ? 'error' : ''; ?>">
            <?php echo htmlspecialchars($message); ?>
        </p>
    <?php endif; ?>

    <form method="POST">
        <table>
            <thead>
                <tr>
                    <th>Pergunta</th>
                    <th>Muito Abaixo</th>
                    <th>Abaixo</th>
                    <th>Esperado</th>
                    <th>Acima</th>
                    <th>Muito Acima</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($pergunta = $perguntas->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($pergunta['pergunta']); ?></td>
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <td>
                                <input type="radio" name="conceito[<?php echo $pergunta['id']; ?>]" value="<?php echo $i; ?>">
                            </td>
                        <?php endfor; ?>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <button type="submit" class="add-button">Enviar Avaliação</button>
    </form>
</div>

</body>
</html>


