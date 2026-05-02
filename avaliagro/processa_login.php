<?php
// Inicia a sessão para salvar os dados do usuário logado
session_start();

// Importa os arquivos necessários
require_once 'includes/BD/Config.php';
require_once 'classes/Auth.class.php';

// Verifica se os dados foram enviados via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 1. Instancia a conexão com o banco
    $database = new Database();
    $db = $database->getConnection();

    // 2. Instancia a classe de Autenticação
    $auth = new Auth($db);

    // 3. Sanitiza as entradas (limpeza básica para evitar espaços extras)
    $login = trim($_POST['login']);
    $senha = $_POST['senha'];

    // 4. Tenta realizar o login
    $usuario_logado = $auth->login($login, $senha);

    if ($usuario_logado) {
        // Sucesso: Registra os dados na sessão
        $_SESSION['usuario_id']    = $usuario_logado['id'];
        $_SESSION['usuario_nome']  = $usuario_logado['nome'];
        $_SESSION['usuario_cliente'] = $usuario_logado['cliente'];
        
        // Proteção extra: Regenera o ID da sessão para evitar sequestro de sessão
        session_regenerate_id(true);

        // Redireciona para o Dashboard
        header("Location: hub.php");
        exit();
    } else {
        // Falha: Redireciona de volta com uma mensagem de erro
        header("Location: index.php?erro=1");
        exit();
    }
} else {
    // Se tentarem acessar o arquivo diretamente, manda de volta para o login
    header("Location: index.php");
    exit();
}