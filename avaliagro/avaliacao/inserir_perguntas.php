<?php
require_once '../classes/cargo.php';
require_once '../ini.php';
require_once '../includes/BD/consultas.php';
require_once '../html/menu.php';


// Buscar clientes
$clientes = $conn->query("SELECT id, nome FROM cliente");

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
        <div class="form-group">
            <label for="nome_avaliacao">Nome da Avaliação:</label>
            <input type="text" id="nome_avaliacao" required>
        </div>

        <div class="form-group">
            <label for="cliente">Cliente:</label>
            <select id="cliente" required>
                <option value="">Selecione um Cliente</option>
                <?php while ($cliente = $clientes->fetch_assoc()) { ?>
                    <option value="<?= $cliente['id'] ?>"><?= $cliente['nome'] ?></option>
                <?php } ?>
            </select>
        </div>

        <div id="perguntasContainer" class="perguntas-container"></div>

        <button type="button" class="add-pergunta-btn" id="addPergunta">➕ Adicionar Pergunta</button>

        <button type="submit" class="submit-btn">Salvar Avaliação</button>
        <p class="erro" id="erro_peso">A soma dos pesos deve ser 1!</p>
    </form>
</div>

<script>
$(document).ready(function () {
    let perguntaIndex = 0;

    // Carregar usuários do cliente selecionado
    $("#cliente").change(function () {
        let clienteId = $(this).val();
        $(".usuarios").empty();

        if (clienteId) {
            $.ajax({
                url: "buscar_usuarios.php",
                type: "POST",
                data: { id_cliente: clienteId },
                success: function (data) {
                    $(".usuarios").each(function () {
                        $(this).html(data);
                    });
                }
            });
        }
    });

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
                
                <label>Responsáveis:</label>
                <select class="usuarios" multiple required></select>
                
                <span class="remove-pergunta" onclick="removerPergunta(${perguntaIndex})">❌</span>
            </div>
        `;
        $("#perguntasContainer").append(perguntaHtml);
        $("#cliente").trigger("change");

        perguntaIndex++;
    });

    // Remover pergunta
    window.removerPergunta = function (index) {
        $("#pergunta_" + index).remove();
    };

    // Validação da soma dos pesos
    $("#formAvaliacao").submit(function (e) {
        e.preventDefault();
        
        let totalPeso = 0;
        $(".peso").each(function () {
            totalPeso += parseFloat($(this).val()) || 0;
        });

        if (totalPeso.toFixed(2) !== "1.00") {
            $("#erro_peso").show();
            return;
        }

        $("#erro_peso").hide();

        let formData = {
            nome_avaliacao: $("#nome_avaliacao").val(),
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
            }
        });
    });
});
</script>

</body>
</html>