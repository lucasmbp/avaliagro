<?php
require_once '../ini.php';
require_once '../includes/BD/consultas.php';
require_once '../classes/usuario.php';

//$result = $conn->query("$LIST_CLIENTES LIMIT $limite OFFSET $offset");

// Buscar clientes no banco de dados
$query = $conn->query("$LIST_CLIENTES");
$clientes = $query->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Avaliação</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

    <h2>Criar Avaliação</h2>
    <form action="salvar_avaliacao.php" method="POST">
        <label for="nome_avaliacao">Nome da Avaliação:</label>
        <input type="text" id="nome_avaliacao" name="nome_avaliacao" required>

        <label for="cliente">Cliente:</label>
			<select id="cliente" name="cliente" required>
				<option value="">Selecione um cliente</option>
				<?php while ($cliente = $query->fetch_assoc()):?>	
						 <option value="<?php echo $cliente['id']; ?>">                            			 
                           			 <?php echo htmlspecialchars($cliente['nome']); ?>
                         </option>
				<?php endwhile; ?>
			</select>

        <div id="perguntas-container">
            <h3>Perguntas</h3>
            <button type="button" id="adicionar-pergunta">+ Adicionar Pergunta</button>
        </div>

        <br>
        <button type="submit">Salvar Avaliação</button>
    </form>

    <script>
        $(document).ready(function() {
            $("#cliente").change(function() {
                var cliente_id = $(this).val();
                if (cliente_id) {
                    $.ajax({
                        url: 'buscar_avaliadores.php',
                        type: 'POST',
                        data: { cliente_id: cliente_id },
                        success: function(response) {
                            $(".avaliador-select").html(response);
                        }
                    });
                }
            });

            $("#adicionar-pergunta").click(function() {
                var cliente_id = $("#cliente").val();
                if (!cliente_id) {
                    alert("Selecione um cliente primeiro!");
                    return;
                }

                var perguntaHtml = `
                    <div class="pergunta-item">
                        <label>Pergunta:</label>
                        <input type="text" name="perguntas[]" required>

                        <label>Avaliador Responsável:</label>
                        <select name="avaliadores[]" class="avaliador-select" required>
                            <option value="">Selecione um avaliador</option>
                        </select>

                        <label>Peso (0 a 1):</label>
                        <input type="number" name="pesos[]" step="0.1" min="0" max="1" required>

                        <button type="button" class="remover-pergunta">Remover</button>
                    </div>
                `;

                $("#perguntas-container").append(perguntaHtml);
                
                // Carregar avaliadores para o novo campo
                var cliente_id = $("#cliente").val();
                $.ajax({
                    url: 'buscar_avaliadores.php',
                    type: 'POST',
                    data: { cliente_id: cliente_id },
                    success: function(response) {
                        $(".avaliador-select:last").html(response);
                    }
                });
            });

            $(document).on("click", ".remover-pergunta", function() {
                $(this).closest(".pergunta-item").remove();
            });
        });
    </script>

</body>
</html>
