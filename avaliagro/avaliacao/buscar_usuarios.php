<?php
require_once '../ini.php';
require_once '../includes/BD/consultas.php';
require_once '../html/menu.php';

    $id_cliente = $_POST["id_cliente"];
    $usuarios = $conn->query("SELECT id, nome FROM usuario WHERE cliente = $id_cliente");
    
    $options = "";
    while ($usuario = $usuarios->fetch_assoc()) {
        $options .= "<option value='{$usuario['id']}'>{$usuario['nome']}</option>";
    }
    echo $options;
?>
