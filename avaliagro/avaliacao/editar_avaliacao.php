<?php
session_start();
require_once '../classes/cargo.php';
require_once '../ini.php';
require_once '../includes/BD/consultas.php';
require_once '../html/menu.php';

if (!isset($_SESSION['id']) || !isset($_GET['id'])) {
    die("Erro: Sessão ou ID da avaliação não definidos.");
}

$id_avaliacao = (int)$_GET['avaliacao'];
$cliente = $_SESSION['id'];

// Consulta da avaliação
$consulta_avaliacao = $conn->prepare("SELECT * FROM avaliacoes WHERE id = ? AND id_cliente = ?");
$consulta_avaliacao->bind_param("ii", $id_avaliacao, $cliente);
$consulta_avaliacao->execute();
$avaliacao = $consulta_avaliacao->get_result()->fetch_assoc();

// Consulta das perguntas e avaliadores
$perguntas = $conn->query("SELECT * FROM perguntas WHERE id_avaliacao = $id_avaliacao");
$usuarios = $conn->query("$LIST_USUARIOS WHERE c.id = $cliente");

function listarAvaliadores($conn, $id_pergunta) {
    $query = $conn->query("SELECT id_usuario FROM pergunta_avaliadores WHERE id_pergunta = $id_pergunta");
    $ids = [];
    while ($r = $query->fetch_assoc()) {
        $ids[] = $r['id_usuario'];
    }
    return $ids;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Avaliação</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="../css/inserir_avaliacao.css">
</head>
<body>

<div class="container">
    <h2>Editar Avaliação</h2>
    <form id="formEdicao">
        <input type="hidden" id="avaliacao_id" value="<?=$avaliacao['id']; ?>">
        <input type="hidden" id="cliente" value="<?=$cliente; ?>">

        <div class="form-group">
            <label>Avaliado:</label>
            <select id="avaliado" name="avaliado" required>
                <option value="">Selecione um avaliado</option>
                <?php while ($usuario = $usuarios->fetch_assoc()): ?>
                    <option value="<?=$usuario['id'];?>" <?= $usuario['id'] == $avaliacao['avaliado_id'] ? 'selected' : '' ?>>
                        <?=htmlspecialchars($usuario['nome']);?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <!-- Soma dos pesos -->
        <div class="form-group">
            <label for="soma_pesos">Soma dos Pesos:</label>
            <input type="text" id="soma_pesos" readonly value="0%" style="font-weight:bold; color:#333;">
        </div>

        <div id="perguntasContainer">
            <?php $index = 0; while ($p = $perguntas->fetch_assoc()): 
                $avaliadores = listarAvaliadores($conn, $p['id']); ?>
                <div class="pergunta" id="pergunta_<?=$index;?>">
                    <input type="hidden" class="pergunta_id" value="<?=$p['id'];?>">
                    <label>Pergunta:</label>
                    <input type="text" class="pergunta_texto" value="<?=htmlspecialchars($p['texto']);?>" required>

                    <label>Peso (%):</label>
                    <input type="number" class="peso" value="<?= $p['peso'] * 100; ?>" step="1" min="0" max="100" required>

                    <label>Avaliadores:</label>
                    <select class="usuarios" multiple required>
                        <?php
                        $todosUsuarios = $conn->query("$LIST_USUARIOS WHERE c.id = $cliente");
                        while ($u = $todosUsuarios->fetch_assoc()):
                        ?>
                            <option value="<?=$u['id'];?>" <?= in_array($u['id'], $avaliadores) ? 'selected' : '' ?>>
                                <?=htmlspecialchars($u['nome']);?>
                            </option>
                        <?php endwhile; ?>
                    </select>

                    <span class="remove-pergunta" onclick="removerPergunta(<?=$index;?>)">❌</span>
                </div>
            <?php $index++; endwhile; ?>
        </div>

        <button type="submit" class="submit-btn">Salvar Alterações</button>
        <p class="erro" id="erro_peso" style="display:none; color:red;">A soma dos pesos deve ser 100%!</p>
    </form>
</div>

<script>
$(document).ready(function () {
    function validarPesos() {
        let total = 0;
        $(".peso").each(function () {
            total += parseFloat($(this).val()) || 0;
        });
        $("#soma_pesos").val(`${total.toFixed(0)}%`);
        $("#erro_peso").toggle(total.toFixed(0) !== "100");
        return total.toFixed(0) === "100";
    }

    $(document).on('input', '.peso', validarPesos);
    validarPesos();

    window.removerPergunta = function (index) {
        $("#pergunta_" + index).remove();
        validarPesos();
    };

    $("#formEdicao").submit(function (e) {
        e.preventDefault();
        if (!validarPesos()) return;

        let dados = {
            id_avaliacao: $("#avaliacao_id").val(),
            avaliado: $("#avaliado").val(),
            perguntas: []
        };

        $(".pergunta").each(function () {
            dados.perguntas.push({
                id: $(this).find(".pergunta_id").val(),
                texto: $(this).find(".pergunta_texto").val(),
                peso: $(this).find(".peso").val(),
                avaliadores: $(this).find(".usuarios").val()
            });
        });

        $.ajax({
            url: "atualizar_avaliacao.php",
            type: "POST",
            data: JSON.stringify(dados),
            contentType: "application/json",
            success: function (res) {
                alert("Avaliação atualizada com sucesso!");
                location.href = "lista_avaliacoes.php";
            },
            error: function () {
                alert("Erro ao atualizar a avaliação.");
            }
        });
    });
});
</script>

</body>
</html>
