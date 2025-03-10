<?php
$conn = new mysqli("localhost", "root", "", "avaliagro");

$data = json_decode(file_get_contents("php://input"), true);

$nome_avaliacao = $data["nome_avaliacao"];
$id_cliente = $data["id_cliente"];

$conn->query("INSERT INTO avaliacao (nome, cliente) VALUES ('$nome_avaliacao', $id_cliente)");
$id_avaliacao = $conn->insert_id;

foreach ($data["perguntas"] as $pergunta) {
    $texto = $pergunta["texto"];
    $peso = $pergunta["peso"];
    
    $conn->query("INSERT INTO pergunta (id_avaliacao, texto, peso) VALUES ($id_avaliacao, '$texto', $peso)");
    $id_pergunta = $conn->insert_id;
    
    foreach ($pergunta["responsaveis"] as $id_usuario) {
        $conn->query("INSERT INTO pergunta_usuario (id_pergunta, id_usuario) VALUES ($id_pergunta, $id_usuario)");
    }
}

echo "Avaliação salva com sucesso!";
?>
