<?php
include("php/conexao.php");

/*
// cadastro usuario
*/

if ($_SERVER["REQUEST_METHOD"]== "POST"){

    $Nome = $_POST['Nome'];
    $Email = $_POST['Email'];
    $Senha = $_POST['Senha'];
    $ConfirmarSenha = $_POST['ConfirmarSenha'];

    if (empty($Nome) || empty($Email) || empty($Senha)|| empty($ConfirmarSenha)){
        echo "campos são obrigatorios";//nunca usado ja que os camps tem required
    }else if($Senha != $ConfirmarSenha){
        echo "Senhas diferentes! Corfimre e entre as senhas novamente.";
    }else{
        // Criptografar a senha
        $senhaHash = password_hash($Senha, PASSWORD_DEFAULT);
        $stmt = $mysqli->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
        $stmt->bind_param("sss",$Nome, $Email, $senhaHash);
        if($stmt->execute()){
            echo "usuario cadastrado";
            header("Location: index.php");
            exit;
        }else{
            echo "Erro: ". $stmt->error;
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
</head>
<body>
    <h2>Cadastro</h2>
    <?php if (isset($mensagem)) { echo "<p>$mensagem</p>"; } ?>
    <form method="POST" action="">
        Nome: <input type="text" name="Nome" required><br>
        Email: <input type="email" name="Email" required><br>
        Senha: <input type="password" name="Senha" required><br>
        Confirmar senha: <input type="password" name="ConfirmarSenha" required><br>
        <button type="submit">Cadastrar</button>
    </form>
    <br>
    <br>
    <a href="index.php">
    Voltar a tela de inicial
    </a>
</body>
</html>