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
       // header("Location: index.php");           
       
        
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

