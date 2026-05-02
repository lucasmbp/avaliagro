<?php
// Define a nova senha
$senha_pura = 'breaking';

// Gera o hash usando o algoritmo padrão (BCRYPT)
$senha_hash = password_hash($senha_pura, PASSWORD_DEFAULT);

// Exibe na tela para você copiar
echo "Copie este código para o seu SQL: <br><b>" . $senha_hash . "</b>";
?>