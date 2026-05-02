<?php
// includes/BD/Config.php

class Database {
    private $host = "localhost";
    private $db_name = "avaliagro";
    private $username = "root"; // Altere conforme seu ambiente
    private $password = "";     // Altere conforme seu ambiente
    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            // Criando a conexão usando PDO
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            // Configurando para reportar erros de forma detalhada
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // Define o charset para evitar problemas com acentuação
            $this->conn->exec("set names utf8");
        } catch(PDOException $exception) {
            echo "Erro na conexão: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
?>