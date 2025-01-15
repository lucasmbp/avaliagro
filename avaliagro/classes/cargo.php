<?php
include'../ini.php';

class cargo
{
    public function inserir_cargo($cargo, $id){
        
        
        // Inserir o cargo no banco de dados
        $stmt = $conn->prepare("INSERT INTO cargo (descricao) VALUES (?)");
        $stmt->bind_param("s", $cargo);
        
        if ($stmt->execute())$message = "Cargo inserido com sucesso!";
        else $message = "Erro ao inserir o cargo: " . $stmt->error;
        
        $stmt->close();
        header("Location: index.php");           
       
        
    }
    
    
    public function validar_cargo($cargo, $conn){
        
        //verifica se o cargo já existe
        $stmt = $conn->query("SELECT cargo FROM cargo where cargo = '$cargo'");
        
        if($cargo == $stmt['cargo'])return false;
        else return true;
        
        $stmt->close();
    }
    
    
    
    
}

