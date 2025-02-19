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
        
            $stmt = $conn->prepare("UPDATE cliente SET nome = '$nome', cnpj = '$cnpj', responsavel = '$responsavel'   WHERE id = $id");
        
            if ($stmt->execute()) {
                $message = "Cliente atualizado com sucesso!";
            } else {
                $message = "Erro ao atualizar o usuario: " . $stmt->error;
            }
            
        $stmt->close();
        header("Location: index.php");
    }
    
    
    public function excluir_cliente ($id, $conn){
        $stmt = $conn->prepare("DELETE FROM cliente where id = ?");
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute())$message = "Cliente excluido com sucesso!";
        else $message = "Erro ao inserir o cargo: " . $stmt->error;
        
        $stmt->close();
        
        return $message;
        
    }
    
   
}
?>