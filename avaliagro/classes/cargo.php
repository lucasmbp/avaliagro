<?php
include'../ini.php';

class cargo
{
    public function inserir_cargo($cargo, $conn){
        
        
        // Inserir o cargo no banco de dados
        $stmt = $conn->prepare("INSERT INTO cargo (cargo) VALUES (?)");
        $stmt->bind_param("s", $cargo);
        
        if ($stmt->execute())$message = "Cargo inserido com sucesso!";
        else $message = "Erro ao inserir o cargo: " . $stmt->error;
        
        $stmt->close();                 
    }
    
    
    public function validar_cargo($cargo, $conn){
        
        // Consulta para verificar o usuário
        $stmt = $conn->prepare("SELECT count(cargo) as cargo FROM cargo where cargo = ?");
        $stmt->bind_param("s", $cargo);
        $stmt->execute();
        $result = $stmt->get_result();
        $position = $result->fetch_assoc();
        
        //verifica se o cargo já existe
        if($position['cargo'] !=0 )return false;
        else return true;
        
        $stmt->close();
    }
    
    
    public function editar_cargo($cargo, $id, $conn){
        
        $validacao = new cargo();
        $validacao = $validacao->validar_cargo($cargo, $conn);
        
        //Se a validação retornar falso retorna cargo já existente
        if($validacao == false){
            $message = false;
            return $message;
        }else{           
                // Atualizar o cargo no banco de dados
                $stmt = $conn->prepare("UPDATE CARGO SET cargo = '$cargo'  WHERE id = $id");               
                if ($stmt->execute()) {
                    $message = true;
                } else {
                    $message = false;
                }                      
                $stmt->close();
                return  $message;
        }  

    }
    
    
    
    public function excluir_cargo ($id, $conn){
        $stmt = $conn->prepare("DELETE FROM cargo where id = ?");
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute())$message = "Cargo excluido com sucesso!";
        else $message = "Erro ao inserir o cargo: " . $stmt->error;
        
        $stmt->close();
        
        return $message;
       // header("Location: index.php");           
        
    }
    
}

