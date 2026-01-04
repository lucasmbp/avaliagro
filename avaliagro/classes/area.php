<?php
include '../ini.php';

class area {

    public function inserir_area(string $area, int $cliente, mysqli $conn): string {
        if ($this->validar_area($area, $cliente, $conn)) {
            return "A área já existe";
        }

        $stmt = $conn->prepare("INSERT INTO area (area, cliente) VALUES (?, ?)");
        $stmt->bind_param("si", $area, $cliente);

        $message = $stmt->execute()
            ? "Área inserida com sucesso!"
            : "Erro ao inserir a área: " . $stmt->error;

        $stmt->close();
        return $message;
    }

    public function validar_area(string $area, int $cliente, mysqli $conn): bool {
        $stmt = $conn->prepare("SELECT COUNT(*) as total FROM area WHERE area = ? AND cliente = ?");
        $stmt->bind_param("si", $area, $cliente);
        $stmt->execute();
        $stmt->bind_result($total);
        $stmt->fetch();
        $stmt->close();

        return $total > 0;
    }

    public function editar_area(string $area, int $id, int $cliente, mysqli $conn): string {
        $stmt = $conn->prepare("UPDATE area SET area = ?, cliente = ? WHERE id = ?");
        $stmt->bind_param("sii", $area, $cliente, $id);

        $message = $stmt->execute()
            ? "Área atualizada com sucesso!"
            : "Erro ao atualizar a área: " . $stmt->error;

        $stmt->close();
        return $message;
    }

    public function excluir_area(int $id, mysqli $conn): string {
        $stmt = $conn->prepare("DELETE FROM area WHERE id = ?");
        $stmt->bind_param("i", $id);

        $message = $stmt->execute()
            ? "Área excluída com sucesso!"
            : "Erro ao excluir a área: " . $stmt->error;

        $stmt->close();
        return $message;
    }
}
