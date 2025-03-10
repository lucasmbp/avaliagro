<?php
require_once '../classes/cargo.php';
require_once '../ini.php';
require_once '../includes/BD/consultas.php';
require_once '../html/menu.html';

//$result = $conn->query("$LIST_CLIENTES LIMIT $limite OFFSET $offset");

// Buscar clientes no banco de dados
$query = $conn->query("$LIST_CLIENTES");
$clientes = $query->fetch_assoc();
?>

<?php
// Conectar ao banco de dados
//$conn = new mysqli("localhost", "root", "", "avaliagro");

//// Buscar clientes
$clientes = $conn->query("SELECT id, nome FROM cliente");

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criação de Avaliação</title>
    <link rel="stylesheet" href="../css/inserir_avaliacao.css">
</head>
<body>
    <div class="container">
        <h1>Criação de Avaliação</h1>
        
        <form id="avaliacao-form" action="processar_avaliacao.php" method="POST">
            <div class="form-group">
                <label for="nome_avaliacao">Nome da Avaliação:</label>
                <input type="text" id="nome_avaliacao" name="nome_avaliacao" required>
            </div>
            
            <div class="form-group">
            <label>Cliente:</label>
        	<select id="cliente" name="cliente" required>
				<option value="">Selecione um cliente</option>
				<?php while ($cliente = $query->fetch_assoc()):?>	
						 <option value="<?php echo $cliente['id']; ?>">                            			 
                           			 <?php echo htmlspecialchars($cliente['nome']); ?>
                         </option>
				<?php endwhile; ?>
			</select>
            </div>
            
            <div id="avaliadores-container" class="avaliadores-container">
                <h3>Adicionar Perguntas</h3>
            </div>
            
            <div id="perguntas-container" class="perguntas-container">
                <!-- As perguntas serão adicionadas aqui -->
            </div>
            
            <button type="button" id="add-pergunta-btn" class="add-pergunta-btn">
                <i>+</i> Adicionar Pergunta
            </button>
            
            <button type="submit" class="submit-btn">Salvar Avaliação</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Carregar clientes quando a página carregar
            carregarClientes();
            
            // Configurar manipuladores de eventos
            document.getElementById('cliente').addEventListener('change', carregarAvaliadores);
            document.getElementById('add-pergunta-btn').addEventListener('click', adicionarPergunta);
            
            // Contador para controlar os IDs dos campos de perguntas
            let perguntaCount = 0;
            
            // Função para carregar clientes via AJAX
            function carregarClientes() {
                const xhr = new XMLHttpRequest();
                xhr.open('GET', 'buscar_clientes.php', true);
                
                xhr.onload = function() {
                    if (this.status === 200) {
                        try {
                            const clientes = JSON.parse(this.responseText);
                            const selectCliente = document.getElementById('cliente');
                            
                            clientes.forEach(cliente => {
                                const option = document.createElement('option');
                                option.value = cliente.id;
                                option.textContent = cliente.nome;
                                selectCliente.appendChild(option);
                            });
                        } catch (e) {
                            console.error('Erro ao processar dados dos clientes:', e);
                        }
                    }
                };
                
                xhr.onerror = function() {
                    console.error('Erro na requisição AJAX para buscar clientes');
                };
                
                xhr.send();
            }
            
            // Função para carregar avaliadores do cliente selecionado
            function carregarAvaliadores() {
                const clienteId = document.getElementById('cliente').value;
                
                if (!clienteId) {
                    document.getElementById('avaliadores-container').style.display = 'none';
                    return;
                }
                
                const xhr = new XMLHttpRequest();
                xhr.open('GET', `buscar_avaliadores.php?cliente_id=${clienteId}`, true);
                
                xhr.onload = function() {
                    if (this.status === 200) {
                        try {
                            // Armazenar avaliadores em uma variável global para uso futuro
                            window.avaliadores = JSON.parse(this.responseText);
                            document.getElementById('avaliadores-container').style.display = 'block';
                            
                            // Se já existir perguntas, atualizar os selects de avaliadores
                            atualizarSelectsAvaliadores();
                        } catch (e) {
                            console.error('Erro ao processar dados dos avaliadores:', e);
                        }
                    }
                };
                
                xhr.onerror = function() {
                    console.error('Erro na requisição AJAX para buscar avaliadores');
                };
                
                xhr.send();
            }
            
            // Função para adicionar uma nova pergunta
            function adicionarPergunta() {
                perguntaCount++;
                
                const perguntaItem = document.createElement('div');
                perguntaItem.className = 'pergunta-item';
                perguntaItem.id = `pergunta-${perguntaCount}`;
                
                const html = `
                    <span class="remove-pergunta" onclick="removerPergunta(${perguntaCount})">×</span>
                    <div class="form-group">
                        <label for="pergunta_${perguntaCount}">Pergunta:</label>
                        <input type="text" id="pergunta_${perguntaCount}" name="perguntas[${perguntaCount}][texto]" required>
                    </div>
                    
                    <div class="form-group avaliador-select">
                        <label for="avaliador_${perguntaCount}">Avaliador Responsável:</label>
                        <select id="avaliador_${perguntaCount}" name="perguntas[${perguntaCount}][avaliador_id]" required>
                            <option value="">Selecione um avaliador</option>
                            ${gerarOpcoesAvaliadores()}
                        </select>
                    </div>
                    
                    <div class="peso-container">
                        <label for="peso_${perguntaCount}">Peso:</label>
                        <input type="number" id="peso_${perguntaCount}" name="perguntas[${perguntaCount}][peso]" 
                               class="peso-field" min="0" max="1" step="0.1" required value="1">
                    </div>
                `;
                
                perguntaItem.innerHTML = html;
                document.getElementById('perguntas-container').appendChild(perguntaItem);
            }
            
            // Função para gerar as opções de avaliadores
            function gerarOpcoesAvaliadores() {
                if (!window.avaliadores || window.avaliadores.length === 0) {
                    return '';
                }
                
                return window.avaliadores.map(avaliador => 
                    `<option value="${avaliador.id}">${avaliador.nome}</option>`
                ).join('');
            }
            
            // Função para atualizar todos os selects de avaliadores existentes
            function atualizarSelectsAvaliadores() {
                const selectsAvaliadores = document.querySelectorAll('[id^="avaliador_"]');
                
                selectsAvaliadores.forEach(select => {
                    const valorAtual = select.value;
                    select.innerHTML = '<option value="">Selecione um avaliador</option>' + gerarOpcoesAvaliadores();
                    
                    // Restaurar valor selecionado, se possível
                    if (valorAtual) {
                        select.value = valorAtual;
                    }
                });
            }
            
            // Adicionar a função removerPergunta no escopo global
            window.removerPergunta = function(id) {
                const pergunta = document.getElementById(`pergunta-${id}`);
                if (pergunta) {
                    pergunta.remove();
                }
            };
        });
    </script>
</body>
</html>