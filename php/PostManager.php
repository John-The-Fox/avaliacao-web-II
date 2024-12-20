<?php
include("conexao.php");
class PostManager {
    private $posts = [];

    public function adicionarPost($titulo, $conteudo, $tipo, $autor) {
        global $mysqli;
        $stmt = $mysqli->prepare("INSERT INTO posts (titulo, conteudo, tipo, autor, pontuacao) VALUES (?, ?, ?, ?, 0)");
        $stmt->bind_param("sssi", $titulo, $conteudo, $tipo, $autor);
        $stmt->execute();
    }

    public function removerPost($id) {
        $this->posts = $this->carregarPosts();
        foreach ($this->posts as $post) {
            if ($post->getId() == $id) {
                global $mysqli;

                // Prepara a query para deletar o post
                $stmt = $mysqli->prepare("DELETE FROM posts WHERE id = ?");
                if (!$stmt) {
                    echo "Erro na preparação da query: " . $mysqli->error;
                    return false;
                }

                // Vincula o parâmetro
                $stmt->bind_param("i", $id);

                // Executa a query
                if ($stmt->execute()) {
                    // Verifica se é um post com imagem e remove o arquivo
                    if ($post->getTipo() === 'imagem' && file_exists($post->getConteudo())) {
                        $filePath = realpath($post->getConteudo());
                        unlink($filePath);
                    }
                    echo "Post deletado com sucesso.";
                    return true;
                } else {
                    echo "Erro ao deletar post: " . $stmt->error;
                    return false;
                }
                
            }
        }

    }

    public function atualizarPost($id, $novoTitulo, $novoConteudo) {
        foreach ($this->posts as $post) {
            if ($post->getId() == $id) {
                $post->atualizar($novoTitulo,$novoConteudo);
            }
        }
    }

    public function carregarPosts() {
        global $mysqli;
        $stmt = $mysqli->query("SELECT * FROM posts ORDER BY id DESC");
        $posts = [];

        while ($row = $stmt->fetch_assoc()) {
            $posts[] = new Post($row['id'], $row['titulo'], $row['conteudo'], $row['tipo'], $row['autor'], $row['pontuacao']);
        }
        return $posts;
    }

    public function verificarVoto($postId, $usuarioId){
        global $mysqli;

    $stmt = $mysqli->prepare("SELECT tipo FROM votos WHERE post_id = ? AND usuario_id = ?");
    $stmt->bind_param("ii", $postId, $usuarioId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($voto = $result->fetch_assoc()) {
        return $voto['tipo']; // Retorna 'like' ou 'dislike'
    }
    return null; // Não há voto
    }


    public function exibirPosts() {
        $this->posts = $this->carregarPosts();
        return $this->posts;
    }

    public function curtirPost($id) {
        var_dump($id);
        $this->posts= $this->carregarPosts(); 
        foreach ($this->posts as $post) {
            if ($post->getId() == $id) {
                $post->curtir();
            }
        }
    }
    
    public function descurtirPost($id) {
        $this->posts= $this->carregarPosts();
        foreach ($this->posts as $post) {
            if ($post->getId() == $id) {
                $post->descurtir();
            }
        }
    }
}
?>
