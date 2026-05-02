<?php
// classes/Cliente.class.php

class Cliente {
    private $conn;
    private $table_name = "cliente";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Listar todos os clientes
    public function listar($limite, $offset) {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY nome LIMIT :limite OFFSET :offset";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':limite', (int)$limite, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function contarTodos() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    public function buscarPorId($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Valida se o CNPJ já existe (importante para Compliance)
    public function validarCNPJ($cnpj, $id_ignorar = null) {
        $query = "SELECT id FROM " . $this->table_name . " WHERE cnpj = :cnpj";
        if ($id_ignorar) $query .= " AND id != :id_ignorar";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':cnpj', $cnpj);
        if ($id_ignorar) $stmt->bindParam(':id_ignorar', $id_ignorar);
        $stmt->execute();
        
        return $stmt->rowCount() === 0;
    }

    public function inserir($nome, $cnpj, $responsavel) {
        $query = "INSERT INTO " . $this->table_name . " (nome, cnpj, responsavel) VALUES (:nome, :cnpj, :responsavel)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':cnpj', $cnpj);
        $stmt->bindParam(':responsavel', $responsavel);
        return $stmt->execute();
    }

    public function atualizar($id, $nome, $cnpj, $responsavel) {
        $query = "UPDATE " . $this->table_name . " SET nome = :nome, cnpj = :cnpj, responsavel = :responsavel WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':cnpj', $cnpj);
        $stmt->bindParam(':responsavel', $responsavel);
        return $stmt->execute();
    }

    public function excluir($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}