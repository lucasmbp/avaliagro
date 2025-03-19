<?php

class usuario
{
    
    
    
    public function login($conn, $login, $senha) {
        session_start(); // Garante que a sessão esteja ativa
        
        // Consulta para verificar o usuário
        $stmt = $conn->prepare("SELECT id, senha, perfil, cliente, nome FROM usuario WHERE login = ?");
        $stmt->bind_param("s", $login);
        $stmt->execute();
        
        // Recupera os dados do banco
        $stmt->bind_result($id, $hashedPassword, $perfil, $cliente, $nome);
        
        if ($stmt->fetch()) { // Se encontrou o usuário
            if (password_verify($senha, $hashedPassword)) { // Verifica a senha
                // Armazena os dados do usuário na sessão
                $_SESSION['login'] = $login;
                $_SESSION['id'] = $id;
                $_SESSION['perfil'] = $perfil;
                $_SESSION['cliente'] = $cliente;
                $_SESSION['nome'] = $nome;
                
                // Fecha a conexão e redireciona
                $stmt->close();
                $conn->close();
                header("Location: dashboard.php");
                exit;
            }
        }
        
        // Se usuário ou senha forem inválidos
        $stmt->close();
        $conn->close();
        return "Usuário ou senha inválidos!";
    }
    
    
    public function editar_usuario($id, $nome, $senha, $email, $cliente, $cargo, $area, $perfil, $conn) {
        
        // Consulta para obter a senha atual do usuário
        $stmt = $conn->prepare("SELECT senha FROM usuario WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($hashedPassword);
        $stmt->fetch();
        $stmt->close(); // ✅ Fechando antes de reutilizar a variável $stmt
        
        // Verifica se a senha foi alterada
        if (empty($senha) || password_verify($senha, $hashedPassword)) {
            // Senha não alterada: Atualiza os demais campos
            $stmt = $conn->prepare("UPDATE usuario SET nome = ?, email = ?, cliente = ?, cargo = ?, area = ?, perfil = ? WHERE id = ?");
            $stmt->bind_param("ssiiiii", $nome, $email, $cliente, $cargo, $area, $perfil, $id);
        } else {
            // Senha foi alterada: Recalcula o hash e atualiza tudo
            $senhaHash = password_hash($senha, PASSWORD_BCRYPT);
            $stmt = $conn->prepare("UPDATE usuario SET nome = ?, senha = ?, email = ?, cliente = ?, cargo = ?, area = ?, perfil = ? WHERE id = ?");
            $stmt->bind_param("ssssiiii", $nome, $senhaHash, $email, $cliente, $cargo, $area, $perfil, $id);
        }
        
        // Executa a atualização
        if ($stmt->execute()) {
            $stmt->close();
            header("Location: index.php");
            exit(); // ✅ Interrompe a execução após o redirecionamento
        } else {
            $message = "Erro ao atualizar o usuário: " . $stmt->error;
        }
        
        $stmt->close();
        return $message;
    }
    
 
    public function inserir_usuario($nome, $login, $senha, $email, $cliente_id, $cargo_id, $area_id, $perfil_id, $conn) {
        
        // Verifica se o login já existe no banco
        $stmt = $conn->prepare("SELECT COUNT(login) as total FROM usuario WHERE login = ?");
        $stmt->bind_param("s", $login);
        $stmt->execute();
        $stmt->bind_result($total);
        $stmt->fetch();
        $stmt->close(); // ✅ Fechando o primeiro stmt corretamente
        
        if ($total != 0) {
            return "Esse login já existe, escolha um login diferente";
        }
        
        // Verifica se todos os campos estão preenchidos
        if (empty($nome) || empty($login) || empty($senha) || empty($email) ||
            empty($cliente_id) || empty($cargo_id) || empty($area_id) || empty($perfil_id)) {
                return "Por favor, preencha todos os campos!";
            }
            
            // Protegendo a senha antes de inserir no banco
            $senhaHash = password_hash($senha, PASSWORD_BCRYPT);
            
            // Inserindo no banco de dados
            $stmt = $conn->prepare("INSERT INTO usuario (nome, login, senha, email, cliente, cargo, area, perfil)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssiiii", $nome, $login, $senhaHash, $email, $cliente_id, $cargo_id, $area_id, $perfil_id);
            
            if ($stmt->execute()) {
                $stmt->close();
                header("Location: index.php");
                exit(); // ✅ Para interromper a execução do script após o redirecionamento
            } else {
                $erro = "Erro ao inserir o usuário: " . $stmt->error;
                $stmt->close();
                return $erro;
            }
    }
    
        
    
    public function excluir_usuario ($id, $conn){
        $stmt = $conn->prepare("DELETE FROM usuario where id = ?");
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute())$message = "Usuário excluido com sucesso!";
        else $message = "Erro ao inserir o cargo: " . $stmt->error;
        
        $stmt->close();
        
        return $message;
        // header("Location: index.php");
        
    }
    
    
    
}

