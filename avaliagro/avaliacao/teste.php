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
    <title>Criar Avaliação</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .fade-in {
            animation: fadeIn 0.3s ease-in-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .shake {
            animation: shake 0.5s ease-in-out;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            20%, 60% { transform: translateX(-5px); }
            40%, 80% { transform: translateX(5px); }
        }
        
        .checkbox-container:hover .checkbox-label {
            background-color: #f0f9ff;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">Criar Avaliação</h1>
            
            <form id="evaluationForm" class="space-y-6">
                <!-- Evaluation Name -->
                
                <div>
                    <label for="clientSelect" class="block text-sm font-medium text-gray-700 mb-1">Avaliado</label>
                    <select id="clientSelect" name="clientSelect" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        <option value="">Selecione um cliente</option>
                        <!-- Options will be loaded via JavaScript -->
                    </select>
                </div>
                
                
                
                <div>
                    <label for="evaluationName" class="block text-sm font-medium text-gray-700 mb-1">Nome da Avaliação</label>
                    <input type="text" id="evaluationName" name="evaluationName" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                </div>
                
                <!-- Client Selection -->
                <div>
                    <label for="clientSelect" class="block text-sm font-medium text-gray-700 mb-1">Cliente</label>
                    <select id="clientSelect" name="clientSelect" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        <option value="">Selecione um cliente</option>
                        <!-- Options will be loaded via JavaScript -->
                    </select>
                </div>
                
                <!-- Questions Container -->
                <div id="questionsContainer" class="space-y-4">
                    <!-- Question blocks will be added here -->
                </div>
                
                <!-- Add Question Button -->
                <div class="flex justify-center">
                    <button type="button" id="addQuestionBtn" 
                            class="flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                        <i class="fas fa-plus-circle mr-2"></i> Adicionar Pergunta
                    </button>
                </div>
                
                <!-- Weight Summary -->
                <div id="weightSummary" class="bg-blue-50 p-4 rounded-md hidden">
                    <div class="flex justify-between items-center">
                        <span class="font-medium text-blue-800">Soma dos pesos:</span>
                        <span id="totalWeight" class="font-bold text-blue-900">0.00</span> 
                    </div>
                </div>
                
                <!-- Error Message -->
                <div id="errorMessage" class="bg-red-50 p-4 rounded-md hidden">
                    <div class="flex items-center text-red-800">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <span id="errorText"></span>
                    </div>
                </div>
                
                <!-- Submit Button -->
                <div class="pt-4">
                    <button type="submit" 
                            class="w-full px-6 py-3 bg-green-600 text-white font-medium rounded-md hover:bg-green-700 transition">
                        Salvar Avaliação
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
    
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
    
        // Simulated database data
        const clients = [
            { id: 1, name: "Empresa A" },
            { id: 2, name: "Empresa B" },
            { id: 3, name: "Empresa C" }
        ];
        
        const users = [
            { id: 1, name: "João Silva", id_cliente: 1 },
            { id: 2, name: "Maria Santos", id_cliente: 1 },
            { id: 3, name: "Carlos Oliveira", id_cliente: 2 },
            { id: 4, name: "Ana Pereira", id_cliente: 2 },
            { id: 5, name: "Pedro Costa", id_cliente: 3 }
        ];
        
        // DOM elements
        const clientSelect = document.getElementById('clientSelect');
        const questionsContainer = document.getElementById('questionsContainer');
        const addQuestionBtn = document.getElementById('addQuestionBtn');
        const evaluationForm = document.getElementById('evaluationForm');
        const weightSummary = document.getElementById('weightSummary');
        const totalWeight = document.getElementById('totalWeight');
        const errorMessage = document.getElementById('errorMessage');
        const errorText = document.getElementById('errorText');
        
        // Load clients into select box
        function loadClients() {
            clients.forEach(client => {
                const option = document.createElement('option');
                option.value = client.id;
                option.textContent = client.name;
                clientSelect.appendChild(option);
            });
        }
        
        // Get users by client ID
        function getUsersByClientId(clientId) {
            return users.filter(user => user.id_cliente == clientId);
        }
        
        // Create a new question block
        function createQuestionBlock(index = 0) {
            const questionId = Date.now() + index;
            
            const questionBlock = document.createElement('div');
            questionBlock.className = 'question-block bg-gray-50 p-4 rounded-md border border-gray-200 fade-in';
            questionBlock.dataset.questionId = questionId;
            
            questionBlock.innerHTML = `
                <div class="flex justify-between items-center mb-3">
                    <h3 class="font-medium text-gray-700">Pergunta #${index + 1}</h3>
                    <button type="button" class="delete-question text-red-500 hover:text-red-700 transition" data-question-id="${questionId}">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
                
                <div class="mb-4">
                    <label for="questionText_${questionId}" class="block text-sm font-medium text-gray-700 mb-1">Texto da Pergunta</label>
                    <textarea id="questionText_${questionId}" name="questions[${questionId}][text]" required
                              class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" rows="2"></textarea>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Peso (0%-100%)</label>
                    <input type="number" id="questionWeight_${questionId}" name="questions[${questionId}][weight]" min="0" max="100" step="1" required
                           class="w-24 px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition weight-input"> %
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Responsáveis</label>
                    <div id="responsiblesContainer_${questionId}" class="space-y-2">
                        <div class="text-gray-500 italic">Selecione um cliente primeiro</div>
                    </div>
                </div>
            `;
            
            return questionBlock;
        }
        
        // Load responsibles for a question
        function loadResponsibles(questionId, clientId) {
            const responsiblesContainer = document.getElementById(`responsiblesContainer_${questionId}`);
            
            if (!clientId) {
                responsiblesContainer.innerHTML = '<div class="text-gray-500 italic">Selecione um cliente primeiro</div>';
                return;
            }
            
            const clientUsers = getUsersByClientId(clientId);
            
            if (clientUsers.length === 0) {
                responsiblesContainer.innerHTML = '<div class="text-gray-500 italic">Nenhum responsável encontrado para este cliente</div>';
                return;
            }
            
            responsiblesContainer.innerHTML = '';
            
            clientUsers.forEach(user => {
                const checkboxId = `responsible_${questionId}_${user.id}`;
                
                const checkboxDiv = document.createElement('div');
                checkboxDiv.className = 'checkbox-container flex items-center p-2 rounded-md transition cursor-pointer';
                
                checkboxDiv.innerHTML = `
                    <input type="checkbox" id="${checkboxId}" name="questions[${questionId}][responsibles][]" value="${user.id}"
                           class="mr-3 h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    <label for="${checkboxId}" class="checkbox-label text-gray-700 cursor-pointer">${user.name}</label>
                `;
                
                responsiblesContainer.appendChild(checkboxDiv);
            });
        }
        
        // Update weight summary
        function updateWeightSummary() {
            const weightInputs = document.querySelectorAll('.weight-input');
            let total = 0;
            
            weightInputs.forEach(input => {
                const value = parseFloat(input.value) || 0;
                total += value;
            });
            
            totalWeight.textContent = total.toFixed(2);
            weightSummary.classList.remove('hidden');
            
            // Highlight if not equal to 100
            if (Math.abs(total - 1) > 0.1) {
                weightSummary.classList.add('bg-red-50');
                weightSummary.classList.remove('bg-blue-50');
            } else {
                weightSummary.classList.add('bg-blue-50');
                weightSummary.classList.remove('bg-red-50');
            }
            
            return total;
        }
        
        // Show error message
        function showError(message) {
            errorText.textContent = message;
            errorMessage.classList.remove('hidden');
            errorMessage.classList.add('shake');
            
            setTimeout(() => {
                errorMessage.classList.remove('shake');
            }, 500);
        }
        
        // Hide error message
        function hideError() {
            errorMessage.classList.add('hidden');
        }
        
        // Event Listeners
        document.addEventListener('DOMContentLoaded', () => {
            loadClients();
            addQuestionBtn.click(); // Add first question by default
        });
        
        // Client selection change
        clientSelect.addEventListener('change', (e) => {
            const clientId = e.target.value;
            const questionBlocks = document.querySelectorAll('.question-block');
            
            questionBlocks.forEach(block => {
                loadResponsibles(block.dataset.questionId, clientId);
            });
        });
        
        // Add question button
        addQuestionBtn.addEventListener('click', () => {
            const questionCount = document.querySelectorAll('.question-block').length;
            const questionBlock = createQuestionBlock(questionCount);
            questionsContainer.appendChild(questionBlock);
            
            // Load responsibles if client is selected
            if (clientSelect.value) {
                loadResponsibles(questionBlock.dataset.questionId, clientSelect.value);
            }
            
            // Scroll to the new question
            questionBlock.scrollIntoView({ behavior: 'smooth' });
        });
        
        // Delete question (delegated event)
        questionsContainer.addEventListener('click', (e) => {
            if (e.target.closest('.delete-question')) {
                const questionId = e.target.closest('.delete-question').dataset.questionId;
                const questionBlock = document.querySelector(`.question-block[data-question-id="${questionId}"]`);
                
                questionBlock.classList.add('opacity-0', 'max-h-0', 'overflow-hidden', 'transition-all', 'duration-300');
                
                setTimeout(() => {
                    questionBlock.remove();
                    updateWeightSummary();
                }, 300);
            }
        });
        
        // Weight input change
        questionsContainer.addEventListener('input', (e) => {
            if (e.target.classList.contains('weight-input')) {
                // Validate weight value
                const value = parseFloat(e.target.value);
                
                if (value < 0) {
                    e.target.value = 0;
                } else if (value > 100) {
                    e.target.value = 100;
                }
                
                updateWeightSummary();
            }
        });
        
        // Form submission
        evaluationForm.addEventListener('submit', (e) => {
            e.preventDefault();
            hideError();
            
            // Validate weights
            const total = updateWeightSummary();
            
            if (Math.abs(total - 1) > 0.1) {
                showError(`A soma dos pesos deve ser igual a 100. Atualmente é ${total.toFixed(2)}.`);
                return;
            }
            
            // Here you would normally submit the form to PHP
            // For this example, we'll just show a success message
            alert('Avaliação salva com sucesso!');
            
            // In a real application, you would submit the form:
            // e.target.submit();
        });
    </script>
</body>
</html>