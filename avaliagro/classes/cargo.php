<?php


class cargo
{
    public function inserir_cargo($cargo, $id, $conn){
        
        
        // Buscar os dados do cargo
        
        $total_resultados = $conn->query("SELECT COUNT(*) AS total FROM cargo where cargo = $cargo");
        $total_linhas = $total_resultados->fetch_assoc()['total'];
        if($total_linhas>0){
            $message = "O cargo já existe";
            }else{
       
                // Atualizar o cargo no banco de dados
                $stmt = $conn->prepare("UPDATE cargo SET cargo = ? WHERE id = ?");
                $stmt->bind_param("si", $cargo, $id);
                
                if ($stmt->execute()) {
                    header("Location: index.php");
                    //$message = true;
                } else {
                    $message = "Erro ao atualizar o cargo: " . $stmt->error;
                }
                $stmt->close();  
        }
        return $message;
       
        
    }
    
    
    
    
}

