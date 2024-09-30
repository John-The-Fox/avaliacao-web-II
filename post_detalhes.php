<?php
require_once 'php/Post.php';
require_once 'php/PostManager.php';
require_once 'php/Comment.php';

session_start();

if (!isset($_SESSION['postManager']) || !isset($_GET['id'])) {
    echo "Post não encontrado.";
    exit;
}

$postId = $_GET['id'];
$postManager = $_SESSION['postManager'];
$post = null;

foreach ($postManager->exibirPosts() as $p) {
    if ($p->getId() == $postId) {
        $post = $p;
        break;
    }
}

if (!$post) {
    echo "Post não encontrado.";
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

// Lógica para curtir e descurtir
if (isset($_GET['curtir'])) {
    $postManager->curtirPost($postId);
    header("Location: post_detalhes.php?id=$postId");
    exit;
}

if (isset($_GET['descurtir'])) {
    $postManager->descurtirPost($postId);
    header("Location: post_detalhes.php?id=$postId");
    exit;
}

// Lógica para exclusão
if (isset($_POST['delete'])) {
    $postManager->removerPost($postId);
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Detalhes do Post</title>
</head>
<body>
    <h1><?php echo $post->getTitulo(); ?></h1>
    <?php
    echo $post->exibirPost();
    echo "<p>Autor: {$post->getAutor()}</p>";
    ?>

    <form action="post_detalhes.php?id=<?php echo $postId; ?>" method="post">
        <button type="submit" name="delete">Excluir Post</button>
        <a href="editar_post.php?id=<?php echo $postId; ?>">Editar Post</a>
    </form>

    <p><a href="post_detalhes.php?id=<?php echo $postId; ?>&curtir=1">Like</a> | 
    <a href="post_detalhes.php?id=<?php echo $postId; ?>&descurtir=1">Dislike</a></p>

    <h3>Comentários</h3>
    <form action="post_detalhes.php?id=<?php echo $postId; ?>" method="post">
        <textarea name="comentario" placeholder="Escreva seu comentário"></textarea>
        <button type="submit">Enviar Comentário</button>
    </form>

    <div class="comentarios">
        <?php foreach ($post->getComentarios() as $comentario): ?>
            <div class="comentario">
                <p><?php echo $comentario->getTexto(); ?> - <strong><?php echo $comentario->getAutor(); ?></strong></p>
                <form action="post_detalhes.php?id=<?php echo $postId; ?>" method="post" style="display:inline;">
                    <input type="hidden" name="commentId" value="<?php echo $comentario->getId(); ?>">
                    <button type="submit" name="deleteComment">Excluir Comentário</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>

    <a href="index.php">Voltar para a página principal</a>
</body>
</html>
