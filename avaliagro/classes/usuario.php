<?php

class Usuario
{
    public function login($conn, $login, $senha)
    {
        session_start();

        $stmt = $conn->prepare("SELECT id, senha, perfil, cliente, nome FROM usuario WHERE login = ?");
        $stmt->bind_param("s", $login);
        $stmt->execute();
        $stmt->bind_result($id, $hashedPassword, $perfil, $cliente, $nome);

        if ($stmt->fetch()) {
            if (password_verify($senha, $hashedPassword)) {
                $_SESSION['login'] = $login;
                $_SESSION['id'] = $id;
                $_SESSION['perfil'] = $perfil;
                $_SESSION['cliente'] = $cliente;
                $_SESSION['nome'] = $nome;

                $stmt->close();
                $conn->close();
                header("Location: dashboard.php");
                exit;
            }
        }

        $stmt->close();
        $conn->close();
        return "Usuário ou senha inválidos!";
    }

    public function editar_usuario($id, $nome, $senha, $email, $cliente, $cargo, $area, $perfil, $conn)
    {
        $stmt = $conn->prepare("SELECT senha FROM usuario WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($hashedPassword);
        $stmt->fetch();
        $stmt->close();

        if (empty($senha) || password_verify($senha, $hashedPassword)) {
            $stmt = $conn->prepare("UPDATE usuario SET nome = ?, email = ?, cliente = ?, cargo = ?, area = ?, perfil = ? WHERE id = ?");
            $stmt->bind_param("ssiiiii", $nome, $email, $cliente, $cargo, $area, $perfil, $id);
        } else {
            $senhaHash = password_hash($senha, PASSWORD_BCRYPT);
            $stmt = $conn->prepare("UPDATE usuario SET nome = ?, senha = ?, email = ?, cliente = ?, cargo = ?, area = ?, perfil = ? WHERE id = ?");
            $stmt->bind_param("ssssiiii", $nome, $senhaHash, $email, $cliente, $cargo, $area, $perfil, $id);
        }

        if ($stmt->execute()) {
            $stmt->close();
            header("Location: index.php");
            exit;
        } else {
            $erro = "Erro ao atualizar o usuário: " . $stmt->error;
            $stmt->close();
            return $erro;
        }
    }

    public function inserir_usuario($nome, $login, $senha, $email, $cliente_id, $cargo_id, $area_id, $perfil_id, $conn)
    {
        $stmt = $conn->prepare("SELECT COUNT(*) FROM usuario WHERE login = ?");
        $stmt->bind_param("s", $login);
        $stmt->execute();
        $stmt->bind_result($total);
        $stmt->fetch();
        $stmt->close();

        if ($total != 0) {
            return "Esse login já existe, escolha um login diferente";
        }

        if (empty($nome) || empty($login) || empty($senha) || empty($email) ||
            empty($cliente_id) || empty($cargo_id) || empty($area_id) || empty($perfil_id)) {
            return "Por favor, preencha todos os campos!";
        }

        $senhaHash = password_hash($senha, PASSWORD_BCRYPT);

        $stmt = $conn->prepare("INSERT INTO usuario (nome, login, senha, email, cliente, cargo, area, perfil)
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssiiii", $nome, $login, $senhaHash, $email, $cliente_id, $cargo_id, $area_id, $perfil_id);

        if ($stmt->execute()) {
            $stmt->close();
            header("Location: index.php");
            exit;
        } else {
            $erro = "Erro ao inserir o usuário: " . $stmt->error;
            $stmt->close();
            return $erro;
        }
    }

    public function excluir_usuario($id, $conn)
    {
        $stmt = $conn->prepare("DELETE FROM usuario WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            $message = "Usuário excluído com sucesso!";
        } else {
            $message = "Erro ao excluir o usuário: " . $stmt->error;
        }

        $stmt->close();
        return $message;
    }
}
?>
