<?php
include("php/conexao.php");
require_once 'php/Post.php';
require_once 'php/usuario.php';
require_once 'php/PostManager.php';


session_start(); // Inicia a sessão

// Inicializa o PostManager na sessão, caso ainda não esteja inicializado
if (!isset($_SESSION['postManager'])) {
    $_SESSION['postManager'] = new PostManager();
}

$usuario = $_SESSION['usuario'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'];
    $tipo = $_POST['tipo'];
    $conteudo = '';

    // Verifica se o post é de texto ou imagem
    if ($tipo === 'texto') {
        $conteudo = $_POST['conteudo'];
    } elseif ($tipo === 'imagem' && isset($_FILES['imagem'])) {
        // Upload da imagem e salvando o caminho
        $targetDir = "img/";
        $targetFile = $targetDir . basename($_FILES["imagem"]["name"]);
        if (move_uploaded_file($_FILES["imagem"]["tmp_name"], $targetFile)) {
            $conteudo = $targetFile;
        } else {
            echo "Erro ao carregar a imagem.";
            exit;
        }
    }

    // Adiciona o novo post ao PostManager na sessão
    //$novoPost = new Post(rand(), $titulo, $conteudo, $tipo, $usuario->getId()); // ID aleatório para o post
    $_SESSION['postManager']->adicionarPost($titulo, $conteudo, $tipo, $usuario->getId());

    // Redireciona de volta para a página principal
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Post</title>
    <script>
        // Função para exibir/esconder campos com base no tipo selecionado
        function atualizarCampos() {
            var tipo = document.getElementById('tipo').value;
            var conteudoCampo = document.getElementById('conteudoCampo');
            var imagemCampo = document.getElementById('imagemCampo');
            
            if (tipo === 'texto') {
                conteudoCampo.style.display = 'block';
                imagemCampo.style.display = 'none';
            } else if (tipo === 'imagem') {
                conteudoCampo.style.display = 'none';
                imagemCampo.style.display = 'block';
            }
        }
        
        // Chama a função ao carregar a página para garantir que os campos corretos estejam visíveis
        window.onload = function() {
            atualizarCampos();
        };
    </script>
</head>
<body>
    <h1>Cadastrar Novo Post</h1>
    
    <form action="cadastro_post.php" method="post" enctype="multipart/form-data">
        <label for="titulo">Título:</label>
        <input type="text" name="titulo" id="titulo" required>
        
        <label for="tipo">Tipo de Post:</label>
        <select name="tipo" id="tipo" required onchange="atualizarCampos()">
            <option value="texto">Texto</option>
            <option value="imagem">Imagem</option>
        </select>
        
        <!-- Campo de Conteúdo (Texto) -->
        <div id="conteudoCampo">
            <label for="conteudo">Conteúdo (Texto):</label>
            <textarea name="conteudo" id="conteudo"></textarea>
        </div>
        
        <!-- Campo de Imagem -->
        <div id="imagemCampo">
            <label for="imagem">Imagem (se aplicável):</label>
            <input type="file" name="imagem" id="imagem">
        </div>
        
        <button type="submit">Cadastrar Post</button>
    </form>

    <script>
        // Garantir que o campo correto esteja visível ao carregar a página
        document.addEventListener("DOMContentLoaded", function() {
            atualizarCampos();
        });
    </script>
</body>
</html>
