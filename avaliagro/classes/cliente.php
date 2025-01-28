<?php 

class cliente{
    
    public function inserir_cliente($nome, $cnpj, $responsavel, $conn){
        
        if ($nome && $cnpj && $responsavel) {
            
            
            // Inserir no banco de dados
            $stmt = $conn->prepare("INSERT INTO cliente (nome, cnpj, responsavel) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $nome, $cnpj, $responsavel);
            
            if ($stmt->execute()) {
                $message = "Cliente inserido com sucesso!";
                $stmt->close();
                header("Location: index.php");
            } else {
                $message = "Erro ao inserir o cliente: " . $stmt->error;
            }
            
            $stmt->close();
        } else {
            $message = "Por favor, preencha todos os campos!";
        }
    }
    
    public function editar_cliente ($nome, $cnpj, $responsavel, $id, $conn){
        
        
        // Consulta para verificar o usuário
        $stmt = $conn->prepare("SELECT * FROM cliente WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->fetch();
        
        
        if (($hashedPassword && password_verify($senha, $hashedPassword))|| $senha=="") {
            
            // Atualizar o usuario no banco de dados
            $stmt = $conn->prepare("UPDATE usuario SET nome = '$nome',email = '$email', cliente = $cliente, cargo = $cargo, area = $area, perfil = $perfil  WHERE id = $id");
            
            if ($stmt->execute()) {
                $message = "usuario atualizado com sucesso!";
            } else {
                $message = "Erro ao atualizar o usuario: " . $stmt->error;
            }
            
            
        }else{
            $senha = password_hash($senha, PASSWORD_BCRYPT);
            // Atualizar o usuario no banco de dados
            $stmt = $conn->prepare("UPDATE usuario SET nome = '$nome', senha = '$senha', email = '$email', cliente = $cliente, cargo = $cargo, area = $area, perfil = $perfil  WHERE id = $id");
            
            
            if ($stmt->execute()) {
                $message = "usuario atualizado com sucesso!";
            } else {
                $message = "Erro ao atualizar o usuario: " . $stmt->error;
            }
            
        }
        
        
        $stmt->close();
        header("Location: index.php");
    }
   
}
?>