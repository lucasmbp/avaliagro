<?php
include '../ini.php';

class cargo {

    public function inserir_cargo(string $cargo, mysqli $conn): string {
        $stmt = $conn->prepare("INSERT INTO cargo (cargo) VALUES (?)");
        $stmt->bind_param("s", $cargo);

        $message = $stmt->execute()
            ? "Cargo inserido com sucesso!"
            : "Erro ao inserir o cargo: " . $stmt->error;

        $stmt->close();
        return $message;
    }

    public function validar_cargo(string $cargo, mysqli $conn): bool {
        $stmt = $conn->prepare("SELECT COUNT(*) as total FROM cargo WHERE cargo = ?");
        $stmt->bind_param("s", $cargo);
        $stmt->execute();
        $stmt->bind_result($total);
        $stmt->fetch();
        $stmt->close();

        return $total === 0;
    }

    public function editar_cargo(string $cargo, int $id, mysqli $conn): bool {
        if (!$this->validar_cargo($cargo, $conn)) {
            return false;
        }

        $stmt = $conn->prepare("UPDATE cargo SET cargo = ? WHERE id = ?");
        $stmt->bind_param("si", $cargo, $id);
        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }

    public function excluir_cargo(int $id, mysqli $conn): string {
        $stmt = $conn->prepare("DELETE FROM cargo WHERE id = ?");
        $stmt->bind_param("i", $id);

        $message = $stmt->execute()
            ? "Cargo excluído com sucesso!"
            : "Erro ao excluir o cargo: " . $stmt->error;

        $stmt->close();
        return $message;
    }
}
