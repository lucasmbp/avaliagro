<?php
require_once '../ini.php';
require_once '../includes/BD/consultas.php';
require_once '../html/menu.php';

$message = "";

// Validação da avaliação
$avaliacao_id = isset($_GET['avaliacao_id']) ? (int)$_GET['avaliacao_id'] : 0;
$competencia_id = isset($_GET['competencia_id']) ? (int)$_GET['competencia_id'] : 0;


// Buscar perguntas da avaliação
$perguntas = $conn->query("$LIST_PERGUNTA WHERE per.avaliacao = $avaliacao_id");

// Buscar dados do avaliado
$dados_avaliado = $conn->query("
    SELECT ava.nome AS avaliado, car.cargo AS cargo, are.area AS area
    FROM avaliacao ava
    JOIN usuario usu ON usu.id = ava.avaliado
    JOIN cargo car ON car.id = usu.cargo
    JOIN area are ON are.id = usu.area
    WHERE ava.id = $avaliacao_id
");

$avaliado = $dados_avaliado->fetch_assoc();

// Processamento do formulário
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    
    if (isset($_POST['conceito']) && is_array($_POST['conceito'])) {
        $conn->begin_transaction(); // Iniciar transação para garantir consistência
        try {
            foreach ($_POST['conceito'] as $pergunta_id => $conceito) {
                $pergunta_id = (int)$pergunta_id;
                $conceito = (int)$conceito;

                $stmt = $conn->prepare("
                    INSERT INTO resultado_avaliacao (avaliacao_id, pergunta_id, avaliador_id, conceito, competencia_id) 
                    VALUES (?, ?, ?, ?, ?)
                ");
                $stmt->bind_param("iiiii", $avaliacao_id, $pergunta_id, $id_sessao, $conceito, $competencia_id);
                $stmt->execute();
            }

            $conn->commit(); // Confirma a transação
            
            $stmt = $conn->prepare("INSERT INTO avaliacao_feedback (avaliacao_id, feedback) VALUES (?, ?)");
            $stmt->bind_param("is", $avaliacao_id, $_POST['feedback'] );
            $stmt->execute();
            
            $message = "Avaliação enviada com sucesso!";
            header("Location: list_avaliacao.php");
        } catch (Exception $e) {
            $conn->rollback(); // Reverte em caso de erro
            $message = "Erro ao salvar avaliação: " . $e->getMessage();
        }
    } else {
        $message = "Erro: Nenhuma resposta foi selecionada.";
    }
}
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
                    <th>Muito abaixo do esperado</th>
                    <th>Abaixo do esperado</th>
                    <th>Esperado</th>
                    <th>Acima do esperado</th>
                    <th>Muito acima do esperado</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($pergunta = $perguntas->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($pergunta['pergunta']); ?></td>
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <td>
                                <input type="radio" name="conceito[<?php echo $pergunta['id']; ?>]" value="<?php echo $i; ?>" required>
                            </td>
                        <?php endfor; ?>
                    </tr>     
                <?php endwhile; ?>
            </tbody>
        </table>
        <br>

        <label for="feedback">Pontos fortes, pontos de desenvolvimento e plano de ação para crescimento:</label>
        <input type="textarea" name="feedback" required>
		<input type="hidden" name="competencia_id" value="<?php echo $competencia_id; ?>">
        <br><br> 
        
        <button type="submit" class="add-button">Enviar Avaliação</button>
    </form>
</div>

</body>
</html>
