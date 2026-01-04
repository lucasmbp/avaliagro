<?php
require_once '../ini.php';

header('Content-Type: application/json');

// Verifica se o conteúdo é JSON
$input = json_decode(file_get_contents("php://input"), true);

if (!$input || !isset($input['id_avaliacao']) || !isset($input['avaliado']) || !isset($input['perguntas'])) {
    http_response_code(400);
    echo json_encode(["erro" => "Dados inválidos."]);
    exit;
}

$id_avaliacao = (int) $input['id_avaliacao'];
$avaliado_id = (int) $input['avaliado'];
$perguntas = $input['perguntas'];

// Atualiza o avaliado da avaliação
$update_avaliacao = $conn->prepare("UPDATE avaliacoes SET avaliado_id = ? WHERE id = ?");
$update_avaliacao->bind_param("ii", $avaliado_id, $id_avaliacao);
$update_avaliacao->execute();

// Atualiza perguntas
foreach ($perguntas as $p) {
    $id_pergunta = (int) $p['id'];
    $texto = $conn->real_escape_string($p['texto']);
    $peso = floatval($p['peso']) / 100; // converte de percentual para decimal
    
    // Atualiza a pergunta
    $conn->query("UPDATE perguntas SET texto = '$texto', peso = $peso WHERE id = $id_pergunta");
    
    // Remove avaliadores antigos
    $conn->query("DELETE FROM pergunta_avaliadores WHERE id_pergunta = $id_pergunta");
    
    // Insere novos avaliadores
    if (!empty($p['avaliadores'])) {
        foreach ($p['avaliadores'] as $id_usuario) {
            $id_usuario = (int) $id_usuario;
            $conn->query("INSERT INTO pergunta_avaliadores (id_pergunta, id_usuario) VALUES ($id_pergunta, $id_usuario)");
        }
    }
}

echo json_encode(["sucesso" => "Avaliação atualizada com sucesso!"]);
