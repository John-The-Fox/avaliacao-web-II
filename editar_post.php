<?php
require_once 'php/Post.php';
require_once 'php/PostManager.php';

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

// Lógica de edição
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $novoTitulo = $_POST['titulo'];
    if ($post->getTipo() === 'texto') {
        $novoConteudo = $_POST['conteudo'];
        $postManager->atualizarPost($postId, $novoTitulo, $novoConteudo);
    } elseif ($post->getTipo() === 'imagem' && isset($_FILES['nova_imagem'])) {
        // Upload da nova imagem e atualiza o caminho
        $targetDir = "img/";
        $targetFile = $targetDir . basename($_FILES["nova_imagem"]["name"]);

        if (move_uploaded_file($_FILES["nova_imagem"]["tmp_name"], $targetFile)) {
            // Remove a imagem antiga, se necessário
            if (file_exists($post->getConteudo())) {
                unlink($post->getConteudo()); // Remove o arquivo da imagem antiga
            }
            $novoConteudo = $targetFile;
            $postManager->atualizarPost($postId, $novoTitulo, $novoConteudo);
        } else {
            echo "Erro ao carregar a nova imagem.";
            exit;
        }
    }
    header("Location: post_detalhes.php?id=$postId");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Post</title>
</head>
<body>
    <h1>Editando: <?php echo $post->getTitulo(); ?></h1>
    
    <form action="editar_post.php?id=<?php echo $postId; ?>" method="post" enctype="multipart/form-data">
        <label for="titulo">Título:</label>
        <input type="text" name="titulo" value="<?php echo $post->getTitulo(); ?>" required>
        
        <?php if ($post->getTipo() === 'texto'): ?>
            <label for="conteudo">Conteúdo:</label>
            <textarea name="conteudo" required><?php echo $post->getConteudo(); ?></textarea>
        <?php elseif ($post->getTipo() === 'imagem'): ?>
            <p>Imagem Atual:</p>
            <img src="<?php echo $post->getConteudo(); ?>" alt="Imagem do Post" style="max-width: 200px;">
            <label for="nova_imagem">Substituir Imagem:</label>
            <input type="file" name="nova_imagem" id="nova_imagem">
        <?php endif; ?>
        
        <button type="submit">Salvar Alterações</button>
    </form>
</body>
</html>
