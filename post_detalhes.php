<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("php/conexao.php");
require_once 'php/Post.php';
require_once 'php/usuario.php';
require_once 'php/PostManager.php';
require_once 'php/Comment.php';

session_start();

if (!isset($_SESSION['postManager']) || !isset($_GET['id'])) {
    echo "Post não encontrado1.";
    exit;
}

$postId = $_GET['id'];
$postManager = $_SESSION['postManager'];
$post = null;
$posts = $postManager->exibirPosts();
if (!empty($posts)) {
    foreach ($posts as $p) {
        if ($p->getId() == $postId){
            $post = $p;
            break;
        }
    }
}else{
    echo "não quis acessar banco de dados ";
}
// Verifica se o usuário está logado
if (isset($_SESSION['usuario']) && $_SESSION['usuario'] instanceof Usuario) {
    $usuario = $_SESSION['usuario'];
}else{
    $usuario = null;
}

if (!$post) {
    echo "Post não encontrado2.";
    exit;
}

// Lógica para adicionar um comentário
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comentario'])) {
    $textoComentario = trim($_POST['comentario']);
    if (!empty($textoComentario)) {
        $novoComentario = new Comment(rand(), $textoComentario, "Usuário Exemplo");
        $post->adicionarComentario($novoComentario);
    }
    header("Location: post_detalhes.php?id=$postId");
    exit;
}

// Lógica para excluir um comentário
if (isset($_POST['deleteComment'])) {
    $comentarioId = $_POST['commentId'];
    $post->removerComentario($comentarioId);
    header("Location: post_detalhes.php?id=$postId");
    exit;
}

// Lógica para curtir ou descurtir um post
if (isset($_GET['curtir']) && $usuario) {
    $postManager->curtirPost($_GET['curtir']);
    header("Location: post_detalhes.php?id=$postId");
    exit;
}

if (isset($_GET['descurtir']) && $usuario) {
    $postManager->descurtirPost($_GET['descurtir']);
    header("Location: post_detalhes.php?id=$postId");
    exit;
}

// Lógica para exclusão
if (isset($_POST['delete'])) {
    if ($usuario && $usuario->getId() == $post->getAutor()) {
        $postManager->removerPost($postId);
        header("Location: index.php");
        exit;
    } else {
        echo "Você não tem permissão para excluir este post.";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Detalhes do Post</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <script>
        function copiarLink() {
            // Cria um elemento temporário para armazenar o link
            var link = window.location.href;
            var tempInput = document.createElement("input");
            tempInput.value = link;
            document.body.appendChild(tempInput);
            tempInput.select();
            document.execCommand("copy");
            document.body.removeChild(tempInput);
            alert("Link copiado para a área de transferência!");
        }
        
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
        <a href="index.php" class="btn-back">Voltar para a página inicial</a>
    </header>
    <main>
        <h1><?php echo $post->getTitulo(); ?></h1>
        <?php
        echo $post->exibirPostDetalhe();
        echo "<p>Autor: {$post->getNomeAutor()}</p>";
        ?>

        <?php if ($usuario && $usuario->getId() == $post->getAutor()): ?>
            <form action="post_detalhes.php?id=<?php echo $postId; ?>" method="post">
                <button type="submit" name="delete">Excluir Post</button>
                <a href="editar_post.php?id=<?php echo $postId; ?>">Editar Post</a>
            </form>
        <?php endif; ?>
        <?php
        if ($usuario){
            $voto = $postManager->verificarVoto($post->getId(), $usuario->getId());
            // Botões Like e Dislike com cores dinâmicas
            $likeClass = ($voto === 'like') ? 'btn-like-active' : 'btn-like';
            $dislikeClass = ($voto === 'dislike') ? 'btn-dislike-active' : 'btn-dislike';

            echo "<p>";
            echo "<a href='post_detalhes.php?id={$postId}&curtir={$post->getId()}' class='$likeClass'>Like</a> ";
            echo "<a href='post_detalhes.php?id={$postId}&descurtir={$post->getId()}' class='$dislikeClass'>Dislike</a>";
            echo "</p>";
        }
        ?>

        <h3>Compartilhar</h3>
        <button onclick="copiarLink()">Copiar Link</button>

        <h3>Comentários</h3>
        <?php if ($usuario): ?>
            <form action="post_detalhes.php?id=<?php echo $postId; ?>" method="post">
            <textarea name="comentario" placeholder="Escreva seu comentário"></textarea>
            <button type="submit">Enviar Comentário</button>
        </form>
        <?php endif; ?>
        <div class="comentarios">
            <?php foreach ($post->getComentarios() as $comentario): ?>
                <div class="comentario">
                    <p><?php echo $comentario->getTexto(); ?> - <strong><?php echo $comentario->getAutorNome(); ?></strong></p>
                    <form action="post_detalhes.php?id=<?php echo $postId; ?>" method="post" style="display:inline;">
                        <input type="hidden" name="commentId" value="<?php echo $comentario->getId(); ?>">
                        <?php if ($usuario->getId() == $post->getAutor() || $usuario->getId() == $comentario->getAutor()): ?>
                        <button type="submit" name="deleteComment">Excluir Comentário</button>
                        <?php endif; ?>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </main>
</body>
</html>
