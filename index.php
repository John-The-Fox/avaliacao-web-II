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
include("php/conexao.php");
require_once 'php/Post.php';
require_once 'php/usuario.php';
require_once 'php/PostManager.php';


session_start(); // Mover esta linha para depois dos require_once

// Inicializa o PostManager na sessão, caso ainda não esteja inicializado
if (!isset($_SESSION['postManager'])) {
    $_SESSION['postManager'] = new PostManager();
}

// Atribui o PostManager à variável local $postManager
$postManager = $_SESSION['postManager'];

// Verifica se o usuário está logado
if (isset($_SESSION['usuario']) && $_SESSION['usuario'] instanceof Usuario) {
    $usuario = $_SESSION['usuario'];
}else{
    $usuario = null;
}


// Lógica para curtir ou descurtir um post
if (isset($_GET['curtir']) && $usuario) {
    $postManager->curtirPost($_GET['curtir']);
    header("Location: index.php");
    exit;
}

if (isset($_GET['descurtir']) && $usuario) {
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
    <link rel="stylesheet" type="text/css" href="css/style.css"> <!-- CSS -->
    <script>
    // Salva a posição do scroll no localStorage
    window.onbeforeunload = function () {
        localStorage.setItem('scrollPosition', window.scrollY);
    };

    // Restaura a posição do scroll após o carregamento
    window.onload = function () {
        const scrollPosition = localStorage.getItem('scrollPosition');
        if (scrollPosition) {
            window.scrollTo(0, parseInt(scrollPosition));
        }
    };
</script>
</head>
<body>
    <header>
        <h1>Bem-vindo ao Sistema de Posts</h1>
        <nav>
            <?php if ($usuario): ?>
                <span>Olá, <?= htmlspecialchars($usuario->getNome()); ?>!</span>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="login.php">Login</a>
                <a href="registrar.php">Registrar</a>
            <?php endif; ?>
        </nav>
    </header>

    <main>
        <div class="posts">
            <?php
            $posts = $postManager->carregarPosts();
            if (!empty($posts)) {
                foreach ($posts as $post) {
                    
                    echo "<div class='post'>";
                    echo "<a href='post_detalhes.php?id={$post->getId()}'><h2>{$post->getTitulo()}</h2></a>";
                    echo $post->exibirPost();
                    // Verifica se o usuário curtiu/descurtiu o post
                    if ($usuario){
                        $voto = $postManager->verificarVoto($post->getId(), $usuario->getId());
                        // Botões Like e Dislike com cores dinâmicas
                        $likeClass = ($voto === 'like') ? 'btn-like-active' : 'btn-like';
                        $dislikeClass = ($voto === 'dislike') ? 'btn-dislike-active' : 'btn-dislike';
                        
                        echo "<a href='index.php?curtir={$post->getId()}' class='$likeClass'>Like</a> ";
                        echo "<a href='index.php?descurtir={$post->getId()}' class='$dislikeClass'>Dislike</a>";
                    }
                    
                    echo "</div>";
                }
            } else {
                echo "<p>Nenhum post disponível.</p>";
            }
            ?>
        </div>
        <?php
            if ($usuario) {
                echo "<br><a href='cadastro_post.php'class='btn-new'>Cadastrar Novo Post</a>";
            }
        ?>
    </main>
</body>
</html>