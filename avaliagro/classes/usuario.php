<?php


class usuario
{
  
    
    public function login($conn,$login, $senha ){
        
        // Consulta para verificar o usuário
        $stmt = $conn->prepare("SELECT senha FROM usuario WHERE login = ?");
        $stmt->bind_param("s", $login);
        $stmt->execute();
        $stmt->bind_result($hashedPassword);
        $stmt->fetch();
        
   
        if ($hashedPassword && password_verify($senha, $hashedPassword)) {
            $_SESSION['login'] = $login;
          
            
            header("Location: dashboard.php");
            exit;
        } else {
            $message = "Usuário ou senha inválidos!";
            return $message;
        }
        
        $stmt->close();
        $conn->close();
        
    }
    
    public function editar_usuario ($nome,$email, $cliente, $cargo, $area, $perfil, $id, $conn){
        
        // Atualizar o usuario no banco de dados
        $stmt = $conn->prepare("UPDATE usuario SET nome = '$nome',email = '$email', cliente = $cliente, cargo = $cargo, area = $area, perfil = $perfil  WHERE id = $id");
        
        if ($stmt->execute()) {
            $message = "usuario atualizado com sucesso!";
        } else {
            $message = "Erro ao atualizar o usuario: " . $stmt->error;
        }
        
        $stmt->close();
        header("Location: index.php");
    }
    
    public function inserir_usuario($nome, $login, $senha, $email, $cliente_id, $cargo_id, $area_id, $perfil_id){
        
        if ($nome && $login && $senha && $email && $cliente_id && $cargo_id && $area_id && $perfil_id) {
            $senhaHash = password_hash($senha, PASSWORD_BCRYPT);
            
            // Inserir no banco de dados
            $stmt = $conn->prepare("INSERT INTO usuario (nome, login, senha, email, cliente, cargo, area, perfil) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssiiii", $nome, $login, $senhaHash, $email, $cliente_id, $cargo_id, $area_id, $perfil_id);
            
            if ($stmt->execute()) {
                $message = "Usuário inserido com sucesso!";
            } else {
                $message = "Erro ao inserir o usuário: " . $stmt->error;
            }
            
            $stmt->close();
        } else {
            $message = "Por favor, preencha todos os campos!";
        }
    }
    
    
    
    
}

