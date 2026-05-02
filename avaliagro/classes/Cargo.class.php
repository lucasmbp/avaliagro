<?php
// classes/Cargo.class.php

class Cargo {
    private $conn;
    private $table_name = "cargo";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Listar cargos com paginação
    public function listar($limite, $offset) {
        $query = "SELECT id, cargo FROM " . $this->table_name . " ORDER BY cargo LIMIT :limite OFFSET :offset";
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

    // Buscar por ID para edição
    public function buscarPorId($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Validar se o cargo já existe (evita duplicados)
    public function validar($nome, $id_ignorar = null) {
        $query = "SELECT id FROM " . $this->table_name . " WHERE cargo = :nome";
        if ($id_ignorar) {
            $query .= " AND id != :id_ignorar";
        }
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nome', $nome);
        if ($id_ignorar) {
            $stmt->bindParam(':id_ignorar', $id_ignorar);
        }
        $stmt->execute();
        return $stmt->rowCount() === 0;
    }

    // Inserir
    public function inserir($nome) {
        $query = "INSERT INTO " . $this->table_name . " (cargo) VALUES (:nome)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nome', $nome);
        return $stmt->execute();
    }

    // Atualizar
    public function atualizar($id, $nome) {
        $query = "UPDATE " . $this->table_name . " SET cargo = :nome WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Excluir
    public function excluir($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}