<?php
    $conn = new mysqli("localhost", "root", "", "avaliagro");

    $id_cliente = $_POST["id_cliente"];
    $usuarios = $conn->query("SELECT id, nome FROM usuario WHERE id_cliente = $id_cliente");

    $options = "";
    while ($usuario = $usuarios->fetch_assoc()) {
        $options .= "<option value='{$usuario['id']}'>{$usuario['nome']}</option>";
    }
    echo $options;
?>
