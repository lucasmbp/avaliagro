<?php
// classes/Area.class.php

class Area {
    private $conn;
    private $table_name = "area";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Listar áreas com paginação e join com cliente
    public function listar($limite, $offset) {
        $query = "SELECT a.id, a.area, c.nome as cliente_nome 
                  FROM " . $this->table_name . " a 
                  LEFT JOIN cliente c ON a.cliente = c.id 
                  LIMIT :limite OFFSET :offset";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':limite', (int)$limite, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Contar total para paginação
    public function contarTodos() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    // Inserir nova área
    public function inserir($nome, $cliente_id) {
        $query = "INSERT INTO " . $this->table_name . " (area, cliente) VALUES (:nome, :cliente)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':cliente', $cliente_id);
        
        return $stmt->execute() ? "Área inserida com sucesso!" : "Erro ao inserir área.";
    }

    // Excluir área
    public function excluir($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
    
    
    // Buscar uma única área para preencher o formulário de edição
    public function buscarPorId($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Atualizar uma área existente
    public function atualizar($id, $nome, $cliente_id) {
        $query = "UPDATE " . $this->table_name . "
              SET area = :nome, cliente = :cliente
              WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':cliente', $cliente_id);
        $stmt->bindParam(':id', $id);
        
        return $stmt->execute();
    }
}