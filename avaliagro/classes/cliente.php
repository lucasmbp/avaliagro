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
   
}
?>