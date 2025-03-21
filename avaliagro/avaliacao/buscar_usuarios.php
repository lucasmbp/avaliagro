<?php
require_once '../ini.php';
require_once '../includes/BD/consultas.php';

    $id_cliente = $_POST["id_cliente"];
    $id_avaliado = $_POST["id_avaliado"];
    $usuarios = $conn->query("SELECT id, nome FROM usuario WHERE cliente = $id_cliente and id not in ($id_avaliado)");
    
    $options = "";
    while ($usuario = $usuarios->fetch_assoc()) {
        $options .= "<option value='{$usuario['id']}'>{$usuario['nome']}</option>";
    }
    echo $options;
?>
