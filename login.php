<?php
include("php/conexao.php");
require_once 'php/usuario.php';
session_start();


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    if (empty($email) || empty($senha)) {
        echo "Preencha todos os campos.";
    } else {
        // Consulta o banco de dados para buscar o usuário
        $stmt = $mysqli->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            // Verifica se a senha fornecida corresponde à armazenada
            if (password_verify($senha, $row['senha'])) {
                // Salva o email do usuário na sessão
                $usuario = new Usuario($row['id'], $row['nome'], $row['email'], $row['senha']);
                $_SESSION['usuario'] = $usuario;
                header("Location: index.php"); // Redireciona para o index
                exit;
            } else {
                echo "Senha inválida.";
            }
        } else {
            echo "Usuário não encontrado.";
        }
        $stmt->close();
    }
}
?>

<h2>Login</h2>
<form method="POST" action="">
    Email: <input type="text" name="email"><br>
    Senha: <input type="password" name="senha"><br>
    <input type="submit" value="Entrar">
</form>
