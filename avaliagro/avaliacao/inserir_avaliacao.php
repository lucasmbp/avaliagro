<?php
session_start();  // Inicia a sessão
require_once '../classes/cargo.php';
require_once '../ini.php';
require_once '../includes/BD/consultas.php';
require_once '../html/menu.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['id'])) {
    die("Erro: Cliente não definido na sessão.");
}

$usuarios = $conn->query("$LIST_USUARIOS WHERE c.id = $cliente_sessao");
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nova Avaliação</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="../css/inserir_avaliacao.css">
</head>
<body>

<div class="container">
    <h2>Criar Avaliação</h2>
    <form id="formAvaliacao">
        <input type="hidden" id="cliente" value="<?=$cliente_sessao; ?>"> <!-- Cliente definido automaticamente -->
        <div class="form-group">
            <label for="nome_avaliacao">Avaliado:</label>
             <select id="avaliado" name="avaliado" required>
                    <option value="">Selecione um avaliado</option>
                    <?php while ($usuario = $usuarios->fetch_assoc()): ?>
                        <option value="<?php echo $usuario['id']; ?>">
                            <?php echo htmlspecialchars($usuario['nome']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
        </div>

        <div id="perguntasContainer" class="perguntas-container"></div>

        <button type="button" class="add-pergunta-btn" id="addPergunta">➕ Adicionar Pergunta</button>
        <button type="submit" class="submit-btn">Salvar Avaliação</button>
        <p class="erro" id="erro_peso">A soma dos pesos deve ser 100%!</p>
    </form>
</div>

<script>
$(document).ready(function () {
    let perguntaIndex = 0;

    // Adicionar nova pergunta
    $("#addPergunta").click(function () {
        let perguntaHtml = `
            <div class="pergunta" id="pergunta_${perguntaIndex}">
                <label>Pergunta:</label>
                <input type="text" class="pergunta_texto" required>
                
                <div class="peso-container">
                    <label>Peso:</label>
                    <input type="number" class="peso peso-field" step="0.01" min="0" max="1" required>
                </div>
                
                <label>Avaliadores:</label>
                <select class="usuarios" id="usuarios_${perguntaIndex}" multiple required></select>
                
                <span class="remove-pergunta" onclick="removerPergunta(${perguntaIndex})">❌</span>
            </div>
        `;

        $("#perguntasContainer").append(perguntaHtml);

        // Carregar usuários do cliente para este novo campo de avaliadores
        carregarUsuarios(`#usuarios_${perguntaIndex}`);

        perguntaIndex++;
    });

    // Remover pergunta e atualizar pesos
    window.removerPergunta = function (index) {
        $("#pergunta_" + index).remove();
        validarPesos();
    };

    // Função para carregar avaliadores do cliente
    function carregarUsuarios(selectId) {
        let clienteId = $("#cliente").val(); // Obtém o cliente da sessão

        if (clienteId) {
            $.ajax({
                url: "buscar_usuarios.php",
                type: "POST",
                data: { id_cliente: clienteId },
                success: function (data) {
                    $(selectId).html(data); // Insere os avaliadores na pergunta específica
                },
                error: function () {
                    console.error("Erro ao buscar avaliadores.");
                }
            });
        }
    }

    // Validação da soma dos pesos
    function validarPesos() {
        let totalPeso = 0;
        $(".peso").each(function () {
            totalPeso += parseFloat($(this).val()) || 0;
        });

        if (totalPeso.toFixed(2) !== "1.00") {
            $("#erro_peso").fadeIn();
        } else {
            $("#erro_peso").fadeOut();
        }
    }

    // Verifica pesos antes de salvar
    $("#formAvaliacao").submit(function (e) {
        e.preventDefault();
        validarPesos();

        if ($("#erro_peso").is(":visible")) {
            return;
        }

        let formData = {
            avaliado: $("#avaliado").val(),
            id_cliente: $("#cliente").val(),
            perguntas: []
        };

        $(".pergunta").each(function () {
            let perguntaTexto = $(this).find(".pergunta_texto").val();
            let peso = $(this).find(".peso").val();
            let responsaveis = $(this).find(".usuarios").val();

            formData.perguntas.push({
                texto: perguntaTexto,
                peso: peso,
                responsaveis: responsaveis
            });
        });

        $.ajax({
            url: "salvar_avaliacao.php",
            type: "POST",
            data: JSON.stringify(formData),
            contentType: "application/json",
            success: function (response) {
                alert(response);
                location.reload();
            },
            error: function () {
                alert("Erro ao salvar a avaliação.");
            }
        });
    });
});

</script>

</body>
</html>
