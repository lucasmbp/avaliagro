<?php
include'../ini.php';

class area{
    
    public function inserir_area($area, $cliente, $conn){
        
        //validar área
        $valida = new area();
        $validacao = $valida->validar_area($area, $cliente, $conn);
        
        if($validacao){
            $message = "A área já existe";
        }else{
            // Inserir o cargo no banco de dados
            $stmt = $conn->prepare("INSERT INTO area (area, cliente) VALUES (?,?)");
            $stmt->bind_param("si", $area, $cliente);
            
            if ($stmt->execute())$message = "Área inserida com sucesso!";
            else $message = "Erro ao inserir o cargo: " . $stmt->error;
            
            $stmt->close();
            
        }
        return $message;
        
    }
    
    
    public function validar_area($area, $cliente, $conn){
        
        //Verifica se a área já existe no banco
        $stmt = $conn->prepare("SELECT COUNT(area) as total FROM area WHERE area = ? and cliente = ?");
        $stmt->bind_param("si", $area, $cliente);
        $stmt->execute();
        
       //retorna a contagem de linhas com a área e cliente informados
        $stmt->bind_result($total);
        $stmt->fetch();
        $stmt->close();
        
        //verifica se o total é igual a 0 a área não existe e rotorna falso
        if($total == 0)return false;
        else return true;

    }
    
    
    public function editar_area($area, $id, $cliente, $conn){      

         // Atualizar o cargo no banco de dados
         $stmt = $conn->prepare("UPDATE area SET area = '$area', cliente = '$cliente'  WHERE id = $id");
         if ($stmt->execute()) {
                $message = "Área atualizada!";
         } else {
                $message = "Erro ao atualizar área";
         }
         $stmt->close();
         return  $message;  
        
    }
    
    
    
    public function excluir_area ($id, $conn){
        $stmt = $conn->prepare("DELETE FROM area where id = ?");
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute())$message = "Área excluida!";
        else $message = "Erro ao excluir área: " . $stmt->error;
        
        $stmt->close();
        
        return $message;
      
        
    }
    
}

