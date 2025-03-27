<?php
require_once '../ini.php';
require_once '../includes/BD/consultas.php';

$data = json_decode(file_get_contents("php://input"), true);

//$nome_avaliacao = $data["nome_avaliacao"];
$avaliado = $data["avaliado"];
$id_cliente = $data["id_cliente"];
$status = 1;

$result = $conn->query("$LIST_USUARIOS where u.id = $avaliado");
$avaliacao_nome = $result->fetch_assoc();
$ava = $avaliacao_nome['nome'];

$conn->query("INSERT INTO avaliacao (cliente, avaliado, nome, estado) VALUES ($id_cliente, $avaliado, '$ava', $status)");
$id_avaliacao = $conn->insert_id;

foreach ($data["perguntas"] as $pergunta) {
    $texto = $pergunta["texto"];
    $peso = $pergunta["peso"];
    
    $conn->query("INSERT INTO pergunta (avaliacao, pergunta, peso, estado) VALUES ($id_avaliacao, '$texto', $peso, $status)");
    $id_pergunta = $conn->insert_id;
    
    foreach ($pergunta["responsaveis"] as $id_usuario) {
        $conn->query("INSERT INTO pergunta_avaliador (pergunta, avaliador) VALUES ($id_pergunta, $id_usuario)");
    }
}

echo "Avaliação salva com sucesso!";
?>
