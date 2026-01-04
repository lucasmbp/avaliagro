<?php
session_start();
require_once '../ini.php'; // Configuração do banco de dados

// Verifica se o usuário está logado
if (!isset($_SESSION['id'])) {
    die(json_encode(["status" => "erro", "mensagem" => "Usuário não autenticado."]));
}

// Obtém o corpo da requisição
$dados = json_decode(file_get_contents("php://input"), true);

if (!$dados || !isset($dados['avaliacao_id'])) {
    die(json_encode(["status" => "erro", "mensagem" => "Dados inválidos."]));
}

$avaliacao_id = intval($dados['avaliacao_id']);
$perguntas = $dados['perguntas'];

// Valida a soma dos pesos apenas das perguntas ativas
$totalPeso = 0;
foreach ($perguntas as $p) {
    if ($p['estado'] == 1) {
        $totalPeso += floatval($p['peso']);
    }
}

if (round($totalPeso, 2) != 1.00) {
    die(json_encode(["status" => "erro", "mensagem" => "A soma dos pesos deve ser exatamente 100% (1.00)."]));
}

// Processa cada pergunta
foreach ($perguntas as $p) {
    $id = isset($p['id']) && is_numeric($p['id']) ? intval($p['id']) : null;
    $texto = isset($p['texto']) ? trim($p['texto']) : "";
    $peso = isset($p['peso']) ? floatval($p['peso']) : 0;
    $estado = isset($p['estado']) ? intval($p['estado']) : 1;
    
    if ($id) {
        // Atualiza perguntas existentes (apenas estado e peso)
        $stmt = $conn->prepare("UPDATE pergunta SET estado = ?, peso = ? WHERE id = ? AND avaliacao = ?");
        $stmt->bind_param("idii", $estado, $peso, $id, $avaliacao_id);
        $stmt->execute();
    } else {
        // Insere novas perguntas
        if (!empty($texto)) {
            $stmt = $conn->prepare("INSERT INTO pergunta (avaliacao, pergunta, peso, estado) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("isdi", $avaliacao_id, $texto, $peso, $estado);
            $stmt->execute();
        }
    }
}

echo json_encode(["status" => "sucesso", "mensagem" => "Avaliação atualizada com sucesso!"]);
?>
