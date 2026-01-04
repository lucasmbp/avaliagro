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
    <title>Nova Avaliação | BOTTREL Agro</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="../css/inserir_avaliacao.css">
    <style>
        :root {
            --primary: #2e7d32;
            --primary-light: #60ad5e;
            --primary-dark: #005005;
            --primary-transparent: rgba(46, 125, 50, 0.1);
            --secondary: #f5f8f5;
            --text: #2d3748;
            --text-light: #718096;
            --text-lighter: #a0aec0;
            --success: #10b981;
            --warning: #f59e0b;
            --error: #ef4444;
            --error-light: #fee2e2;
            --background: #f8fafc;
            --card: #ffffff;
            --border: #e2e8f0;
            --border-hover: #cbd5e0;
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.05);
            --shadow: 0 4px 6px rgba(0,0,0,0.05);
            --shadow-md: 0 8px 15px rgba(0,0,0,0.08);
            --shadow-lg: 0 20px 25px rgba(0,0,0,0.1);
            --radius: 12px;
            --radius-sm: 8px;
            --transition: 200ms cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }
        
        body {
            background-color: var(--background);
            color: var(--text);
            line-height: 1.6;
            min-height: 100vh;
        }
        
        .container {
            max-width: 900px;
            margin: 40px auto;
            padding: 0 20px;
        }
        
        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 40px;
            position: relative;
        }
        
        .page-header h2 {
            font-size: 2.2rem;
            font-weight: 700;
            color: var(--text);
            position: relative;
            display: inline-block;
        }
        
        .page-header h2::after {
            content: "";
            position: absolute;
            bottom: -10px;
            left: 0;
            width: 60px;
            height: 4px;
            background-color: var(--primary);
            border-radius: 2px;
        }
        
        .subheader {
            font-size: 1.1rem;
            color: var(--text-light);
            margin-bottom: 30px;
            margin-top: -15px;
        }
        
        .card {
            background: var(--card);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: 30px;
            margin-bottom: 30px;
            transition: var(--transition);
            border: 1px solid var(--border);
        }
        
        .card-header {
            display: flex;
            align-items: center;
            margin-bottom: 25px;
        }
        
        .card-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--primary-transparent);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
        }
        
        .card-icon i {
            color: var(--primary);
            font-size: 20px;
        }
        
        .card-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--text);
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-group label {
            display: block;
            font-weight: 500;
            margin-bottom: 8px;
            color: var(--text);
        }
        
        input, select {
            width: 100%;
            padding: 12px 16px;
            border-radius: var(--radius-sm);
            border: 1px solid var(--border);
            color: var(--text);
            font-size: 0.95rem;
            transition: var(--transition);
            background-color: var(--card);
        }
        
        input:focus, select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(46, 125, 50, 0.15);
        }
        
        select[multiple] {
            height: 120px;
            padding: 8px;
        }
        
        option {
            padding: 8px 12px;
        }
        
        button {
            cursor: pointer;
            border: none;
            font-weight: 600;
            border-radius: var(--radius-sm);
            transition: var(--transition);
        }
        
        .progress-container {
            background-color: var(--secondary);
            padding: 20px;
            border-radius: var(--radius-sm);
            margin-bottom: 30px;
            position: relative;
            border: 1px dashed var(--border-hover);
        }
        
        .progress-title {
            font-weight: 600;
            font-size: 1rem;
            margin-bottom: 12px;
            color: var(--text);
        }
        
        .progress-bar-container {
            width: 100%;
            height: 10px;
            background-color: #e9ecef;
            border-radius: 5px;
            overflow: hidden;
            margin-bottom: 10px;
        }
        
        .progress-bar {
            height: 100%;
            background-color: var(--primary);
            border-radius: 5px;
            transition: width 0.3s ease-in-out;
        }
        
        .progress-info {
            display: flex;
            justify-content: space-between;
            font-size: 0.9rem;
            color: var(--text-light);
        }
        
        .progress-status {
            font-weight: 600;
        }
        
        .perguntas-container {
            margin-bottom: 30px;
        }
        
        .pergunta {
            position: relative;
            background-color: var(--card);
            padding: 25px;
            border-radius: var(--radius-sm);
            margin-bottom: 20px;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border);
            transition: var(--transition);
        }
        
        .pergunta:hover {
            border-color: var(--border-hover);
            box-shadow: var(--shadow);
        }
        
        .pergunta-header {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .pergunta-number {
            background-color: var(--primary);
            color: white;
            width: 26px;
            height: 26px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-size: 0.8rem;
            font-weight: 600;
            margin-right: 10px;
        }
        
        .pergunta-title {
            font-weight: 600;
            color: var(--text);
        }
        
        .pergunta-grid {
            display: grid;
            grid-template-columns: 3fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .pergunta-grid .form-group {
            margin-bottom: 0;
        }
        
        .peso-container {
            position: relative;
            display: flex;
            align-items: center;
        }
        
        .peso-field {
            padding-right: 40px;
        }
        
        .peso-simbolo {
            position: absolute;
            right: 15px;
            color: var(--text-light);
            font-weight: 500;
            pointer-events: none;
        }
        
        .remove-pergunta {
            position: absolute;
            top: 15px;
            right: 15px;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: var(--error-light);
            color: var(--error);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition);
            font-size: 14px;
        }
        
        .remove-pergunta:hover {
            transform: scale(1.1);
            background-color: var(--error);
            color: white;
        }
        
        .add-pergunta-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: var(--secondary);
            color: var(--primary);
            padding: 12px 20px;
            border-radius: var(--radius-sm);
            font-weight: 600;
            margin-bottom: 25px;
            gap: 8px;
            border: 1px dashed var(--primary-light);
        }
        
        .add-pergunta-btn i {
            font-size: 16px;
        }
        
        .add-pergunta-btn:hover {
            background-color: var(--primary-transparent);
            transform: translateY(-2px);
        }
        
        .submit-btn {
            background-color: var(--primary);
            color: white;
            padding: 14px 25px;
            font-size: 1rem;
            border-radius: var(--radius-sm);
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.2s ease;
        }
        
        .submit-btn:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }
        
        .submit-btn:active {
            transform: translateY(0);
        }
        
        .erro {
            display: none;
            color: var(--error);
            background-color: var(--error-light);
            padding: 15px;
            border-radius: var(--radius-sm);
            margin-top: 20px;
            font-weight: 500;
            border-left: 4px solid var(--error);
        }
        
        .erro i {
            margin-right: 8px;
        }
        
        .no-perguntas {
            text-align: center;
            padding: 40px 20px;
            background-color: var(--secondary);
            border-radius: var(--radius-sm);
            color: var(--text-light);
            margin-bottom: 20px;
            font-size: 1.1rem;
            border: 1px dashed var(--border-hover);
        }
        
        .no-perguntas i {
            font-size: 40px;
            color: var(--text-lighter);
            margin-bottom: 15px;
            display: block;
        }
        
        .action-buttons {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }
        
        /* Animações */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .pergunta {
            animation: fadeIn 0.3s ease-out;
        }
        
        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(46, 125, 50, 0.4);
            }
            70% {
                box-shadow: 0 0 0 10px rgba(46, 125, 50, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(46, 125, 50, 0);
            }
        }
        
        .progress-status.complete {
            color: var(--success);
            animation: pulse 1.5s infinite;
        }
        
        /* Media queries */
        @media (max-width: 768px) {
            .pergunta-grid {
                grid-template-columns: 1fr;
                gap: 15px;
            }
            
            .page-header h2 {
                font-size: 1.8rem;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .submit-btn, .add-pergunta-btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="page-header">
        <h2>Criar Avaliação</h2>
    </div>
    <p class="subheader">Defina as perguntas, pesos e avaliadores para a nova avaliação</p>
    
    <form id="formAvaliacao">
        <input type="hidden" id="cliente" value="<?=$cliente_sessao; ?>">
        
        <div class="card">
            <div class="card-header">
                <div class="card-icon">
                    <i class="fas fa-user"></i>
                </div>
                <h3 class="card-title">Informações Básicas</h3>
            </div>
            
            <div class="form-group">
                <label for="avaliado">Selecione o Avaliado:</label>
                <select id="avaliado" name="avaliado" required>
                    <option value="">Selecione um avaliado</option>
                    <?php while ($usuario = $usuarios->fetch_assoc()): ?>
                        <option value="<?php echo $usuario['id']; ?>">
                            <?php echo htmlspecialchars($usuario['nome']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
        </div>
        
        <!-- Progress bar para o peso total -->
        <div class="progress-container">
            <h4 class="progress-title">
                <i class="fas fa-balance-scale"></i> Distribuição de Pesos
            </h4>
            <div class="progress-bar-container">
                <div class="progress-bar" id="progressBar" style="width: 0%"></div>
            </div>
            <div class="progress-info">
                <span>Total acumulado</span>
                <span class="progress-status" id="pesoTotal">0%</span>
            </div>
        </div>
        
        <div id="perguntasContainer" class="perguntas-container">
            <!-- Placeholder quando não há perguntas -->
            <div class="no-perguntas" id="noPerguntasMsg">
                <i class="fas fa-clipboard-list"></i>
                <p>Nenhuma pergunta adicionada. Clique em "Adicionar Pergunta" para começar.</p>
            </div>
        </div>
        
        <div class="action-buttons">
            <button type="button" class="add-pergunta-btn" id="addPergunta">
                <i class="fas fa-plus"></i> Adicionar Pergunta
            </button>
            <button type="submit" class="submit-btn">
                <i class="fas fa-save"></i> Salvar Avaliação
            </button>
        </div>
        
        <p class="erro" id="erro_peso">
            <i class="fas fa-exclamation-triangle"></i> A soma dos pesos deve ser 100%!
        </p>
    </form>
</div>

<script>
$(document).ready(function () {
    let perguntaIndex = 0;
    
    // Adicionar nova pergunta
    $("#addPergunta").click(function () {
        // Ocultar a mensagem de nenhuma pergunta
        $("#noPerguntasMsg").hide();
        
        const perguntaNumber = $(".pergunta").length + 1;
        
        let perguntaHtml = `
            <div class="pergunta" id="pergunta_${perguntaIndex}">
                <div class="pergunta-header">
                    <div class="pergunta-number">${perguntaNumber}</div>
                    <h4 class="pergunta-title">Pergunta ${perguntaNumber}</h4>
                </div>
                
                <div class="pergunta-grid">
                    <div class="form-group">
                        <label>Texto da Pergunta:</label>
                        <input type="text" class="pergunta_texto" placeholder="Insira a pergunta aqui..." required>
                    </div>
                    
                    <div class="form-group">
                        <label>Peso (%):</label>
                        <div class="peso-container">
                            <input type="number" class="peso peso-field" step="1" min="0" max="100" placeholder="0" required onChange="validarPesos()">
                            <span class="peso-simbolo">%</span>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Selecione os Avaliadores:</label>
                    <select class="usuarios" id="usuarios_${perguntaIndex}" multiple required></select>
                </div>
                
                <div class="remove-pergunta" onclick="removerPergunta(${perguntaIndex})">
                    <i class="fas fa-times"></i>
                </div>
            </div>
        `;

        $("#perguntasContainer").append(perguntaHtml);
        
        // Carregar usuários do cliente para este novo campo de avaliadores
        carregarUsuarios(`#usuarios_${perguntaIndex}`);

        perguntaIndex++;
        validarPesos(); // Atualiza o total após adicionar
        
        // Efeito de scroll automático até a nova pergunta
        $('html, body').animate({
            scrollTop: $(".pergunta:last").offset().top - 100
        }, 500);
    });

    // Remover pergunta e atualizar pesos
    window.removerPergunta = function (index) {
        $("#pergunta_" + index).fadeOut(300, function() {
            $(this).remove();
            
            // Atualizar números das perguntas restantes
            $(".pergunta").each(function(i) {
                $(this).find(".pergunta-number").text(i + 1);
                $(this).find(".pergunta-title").text(`Pergunta ${i + 1}`);
            });
            
            validarPesos();
            
            // Mostrar mensagem se não houver mais perguntas
            if ($(".pergunta").length === 0) {
                $("#noPerguntasMsg").fadeIn();
            }
        });
    };

    // Função para carregar avaliadores do cliente
    function carregarUsuarios(selectId) {
        let clienteId = $("#cliente").val(); // Obtém o ID do cliente
        let avaliadoId = $("#avaliado").val(); // Obtém o ID do avaliado
    
        if (clienteId) {
            $.ajax({
                url: "buscar_usuarios.php",
                type: "POST",
                data: { 
                    id_cliente: clienteId, 
                    id_avaliado: avaliadoId // Passa o avaliado para excluir da lista
                },
                success: function (data) {
                    $(selectId).html(data); // Insere os usuários no select específico
                },
                error: function () {
                    console.error("Erro ao buscar avaliadores.");
                }
            });
        }
    }

    // Atualiza avaliadores quando o avaliado mudar
    $("#avaliado").change(function() {
        $(".usuarios").each(function() {
            let selectId = "#" + $(this).attr('id');
            carregarUsuarios(selectId);
        });
    });

    // Validação da soma dos pesos - agora global para ser chamada de vários lugares
    window.validarPesos = function() {
        let totalPeso = 0;
        $(".peso").each(function () {
            totalPeso += parseInt($(this).val()) || 0;
        });

        // Atualiza o display do peso total
        $("#pesoTotal").text(totalPeso + "%");
        
        // Atualiza a barra de progresso
        const progressWidth = Math.min(totalPeso, 100);
        $("#progressBar").css("width", progressWidth + "%");
        
        // Define classes e cores baseadas no total
        if (totalPeso === 100) {
            $("#pesoTotal").css("color", "var(--success)").addClass("complete");
            $("#progressBar").css("background-color", "var(--success)");
            $("#erro_peso").fadeOut();
        } else if (totalPeso > 100) {
            $("#pesoTotal").css("color", "var(--error)").removeClass("complete");
            $("#progressBar").css("background-color", "var(--error)");
            $("#erro_peso").fadeIn().html('<i class="fas fa-exclamation-triangle"></i> A soma dos pesos excede 100%!');
        } else {
            $("#pesoTotal").css("color", "var(--warning)").removeClass("complete");
            $("#progressBar").css("background-color", "var(--warning)");
            $("#erro_peso").fadeIn().html('<i class="fas fa-exclamation-triangle"></i> A soma dos pesos deve ser 100%!');
        }
    };

    // Monitorar mudanças em todos os campos de peso
    $(document).on('input', '.peso', function() {
        validarPesos();
    });

    // Verifica pesos antes de salvar
    $("#formAvaliacao").submit(function (e) {
        e.preventDefault();
        validarPesos();

        if ($("#erro_peso").is(":visible")) {
            // Role até o erro para garantir que o usuário o veja
            $('html, body').animate({
                scrollTop: $("#erro_peso").offset().top - 100
            }, 500);
            return;
        }
        
        // Verifica se há perguntas
        if ($(".pergunta").length === 0) {
            $("#erro_peso").html('<i class="fas fa-exclamation-triangle"></i> É necessário adicionar pelo menos uma pergunta!').fadeIn();
            return;
        }

        let formData = {
            avaliado: $("#avaliado").val(),
            id_cliente: $("#cliente").val(),
            perguntas: []
        };

        $(".pergunta").each(function () {
            let perguntaTexto = $(this).find(".pergunta_texto").val();
            let pesoPercentual = $(this).find(".peso").val();
            // Converte de percentual para decimal na hora de salvar
            let peso = parseFloat(pesoPercentual) / 100;
            let responsaveis = $(this).find(".usuarios").val();

            formData.perguntas.push({
                texto: perguntaTexto,
                peso: peso, // Salva como decimal no banco (0.XX)
                responsaveis: responsaveis
            });
        });

        $.ajax({
            url: "salvar_avaliacao.php",
            type: "POST",
            data: JSON.stringify(formData),
            contentType: "application/json",
            beforeSend: function() {
                // Desabilitar o botão e mostrar feedback de carregamento
                $(".submit-btn").prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Salvando...');
            },
            success: function (response) {
                alert(response);
                location.reload();
            },
            error: function () {
                alert("Erro ao salvar a avaliação.");
                $(".submit-btn").prop('disabled', false).html('<i class="fas fa-save"></i> Salvar Avaliação');
            }
        });
    });
});
</script>

</body>
</html>