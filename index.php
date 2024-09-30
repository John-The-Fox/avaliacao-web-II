<?php
/*
- Trabalho Parcial = Desenvolva um sistema WEB em PHP que tenha as seguintes opções. FrontEnd
    - Cadastro de imagens. Inserir, deleta, atualizar.
    - Cadastro de textos. Inserir, deletar atualizar.
    - Opções de pontuação.
    - Opções de comentários.
    - Opções de compartilhamento (Pode ser link).
    - Estrutura: Deve estar implementado as estruturas de classes e funções em PHP.
    - Deve conter os nomes dos integrantes do grupo e a matricula.
*/
require_once 'php/Post.php';
require_once 'php/PostManager.php';

session_start(); // Mover esta linha para depois dos require_once

// Inicializa o PostManager na sessão, caso ainda não esteja inicializado
if (!isset($_SESSION['postManager'])) {
    $_SESSION['postManager'] = new PostManager();
}

// Atribui o PostManager à variável local $postManager
$postManager = $_SESSION['postManager'];

// Lógica para curtir ou descurtir um post
if (isset($_GET['curtir'])) {
    $postManager->curtirPost($_GET['curtir']);
    header("Location: index.php");
    exit;
}

if (isset($_GET['descurtir'])) {
    $postManager->descurtirPost($_GET['descurtir']);
    header("Location: index.php");
    exit;
}
?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Sistema de Posts</title>
</head>
<body>
    <h1>Bem-vindo ao Sistema de Posts</h1>
    
    <div class="posts">
        <?php 
        // Garante que o $postManager retorne um array de posts
        $posts = $postManager->exibirPosts();

        if (!empty($posts)) {
            foreach ($posts as $post) {
                echo "<a href='post_detalhes.php?id={$post->getId()}'><h2>{$post->getTitulo()}</h2></a><br>";
                echo $post->exibirPost();
                echo "<a href='index.php?curtir={$post->getId()}'>Like</a> | ";
                echo "<a href='index.php?descurtir={$post->getId()}'>Dislike</a><br>";
            }
        } else {
            echo "Nenhum post disponível.";
        }
        ?>
    </div>

    <a href="cadastro_post.php">Cadastrar Novo Post</a>

</body>
</html>