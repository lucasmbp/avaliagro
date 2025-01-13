<?php
namespace avaliagro\classes;

class usuario
{
    
    public function conn(){
        
        
    }
    
    public function login($conn,$login, $senha ){
        // Consulta para verificar o usuário
        $stmt = $conn->prepare("SELECT senha FROM usuario WHERE login = $login");
        $stmt->bind_param("s", $login);
        $stmt->execute();
        $stmt->bind_result($hashedPassword);
        $stmt->fetch();
        
        
        if ($hashedPassword && password_verify($senha, $hashedPassword)) {
            $_SESSION['login'] = $login;
            //echo "ok";
            //exit;
            
            header("Location: dashboard.php");
            exit;
        } else {
            $message = "Usuário ou senha inválidos!";
        }
        
        $stmt->close();
        $conn->close();
        
    }
    
    
    
    
    
    
}

