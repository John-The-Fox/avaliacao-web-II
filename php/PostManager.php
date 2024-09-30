<?php
class PostManager {
    private $posts = [];

    public function adicionarPost($post) {
        $this->posts[] = $post;
    }

    public function removerPost($id) {
        foreach ($this->posts as $key => $post) {
            if ($post->getId() == $id) {
                // Verifica se é um post com imagem e remove o arquivo
                if ($post->getTipo() === 'imagem' && file_exists($post->getConteudo())) {
                    //unlink($post->getConteudo()); // não exclui a imagem da pasta
                    $filePath = realpath($post->getConteudo());
                    unlink($filePath);//aqui exclui verificar por que o outro não funciona
                }
                unset($this->posts[$key]); // Remove o post
            }
        }
    }

    public function atualizarPost($id, $novoTitulo, $novoConteudo) {
        foreach ($this->posts as $post) {
            if ($post->getId() == $id) {
                $post->setTitulo($novoTitulo);
                $post->setConteudo($novoConteudo);
            }
        }
    }

    public function exibirPosts() {
        return $this->posts;
    }

    public function curtirPost($id) {
        foreach ($this->posts as $post) {
            if ($post->getId() == $id) {
                $post->curtir();
            }
        }
    }
    
    public function descurtirPost($id) {
        foreach ($this->posts as $post) {
            if ($post->getId() == $id) {
                $post->descurtir();
            }
        }
    }
}
?>
