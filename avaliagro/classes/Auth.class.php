<?php
// classes/Auth.class.php

class Auth {
    private $conn;
    private $table_name = "usuario";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function login($login, $senha) {
        // Prepared Statement: O ":" protege contra ataques
        $query = "SELECT id, nome, senha, cliente FROM " . $this->table_name . " WHERE login = :login LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':login', $login);
        $stmt->execute();

        if($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Verifica a senha comparando com o hash do banco
            if(password_verify($senha, $row['senha'])) {
                return $row; // Login bem-sucedido
            }
        }
        return false; // Usuário ou senha incorretos
    }
}
?>