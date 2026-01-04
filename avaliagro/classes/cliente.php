<?php

class cliente {

    public function inserir_cliente($nome, $cnpj, $responsavel, $conn) {
        if ($nome && $cnpj && $responsavel) {
            // Inserir no banco de dados
            $stmt = $conn->prepare("INSERT INTO cliente (nome, cnpj, responsavel) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $nome, $cnpj, $responsavel);

            if ($stmt->execute()) {
                $message = "Cliente inserido com sucesso!";
                $stmt->close();
                header("Location: index.php");
                exit; // ✅ Importante: Finaliza o script após redirecionamento
            } else {
                $message = "Erro ao inserir o cliente: " . $stmt->error;
            }

            $stmt->close();
        } else {
            $message = "Por favor, preencha todos os campos!";
        }

        return $message ?? ''; // ✅ Garante retorno consistente
    }

    public function editar_cliente($nome, $cnpj, $responsavel, $id, $conn) {
        // ✅ Evita SQL Injection utilizando placeholders
        $stmt = $conn->prepare("UPDATE cliente SET nome = ?, cnpj = ?, responsavel = ? WHERE id = ?");
        $stmt->bind_param("sssi", $nome, $cnpj, $responsavel, $id);

        if ($stmt->execute()) {
            $message = "Cliente atualizado com sucesso!";
        } else {
            $message = "Erro ao atualizar o cliente: " . $stmt->error;
        }

        $stmt->close();
        header("Location: index.php");
        exit; // ✅ Garante término após redirecionamento
    }

    public function excluir_cliente($id, $conn) {
        $stmt = $conn->prepare("DELETE FROM cliente WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            $message = "Cliente excluído com sucesso!";
        } else {
            $message = "Erro ao excluir cliente: " . $stmt->error;
        }

        $stmt->close();

        return $message;
    }
}
?>
