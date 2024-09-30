<?php
class Post {
    private $id;
    private $titulo;
    private $conteudo;
    private $tipo;
    private $autor;
    private $pontuacao;
    private $comentarios = [];

    public function __construct($id, $titulo, $conteudo, $tipo, $autor) {
        $this->id = $id;
        $this->titulo = $titulo;
        $this->conteudo = $conteudo;
        $this->tipo = $tipo;
        $this->autor = $autor;
        $this->pontuacao = 0; // Inicia com 0
    }

    // Métodos Getters e Setters
    public function getId() { return $this->id; }
    public function getTitulo() { return $this->titulo; }
    public function getConteudo() { return $this->conteudo; }
    public function getTipo() { return $this->tipo; }
    public function getAutor() { return $this->autor; }
    public function getPontuacao() { return $this->pontuacao; }
    public function getComentarios() { return $this->comentarios; }
    public function setTitulo($novoTitulo) { $this->titulo = $novoTitulo; }
    public function setConteudo($novoConteudo) { $this->conteudo = $novoConteudo; }

    public function adicionarComentario($comentario) {
        $this->comentarios[] = $comentario;
    }

    public function removerComentario($comentarioId) {
        foreach ($this->comentarios as $index => $comentario) {
            if ($comentario->getId() == $comentarioId) {
                unset($this->comentarios[$index]);
                $this->comentarios = array_values($this->comentarios); // Reindexa a lista
                return true;
            }
        }
        return false;
    }

    public function curtir() {
        $this->pontuacao++;
    }

    public function descurtir() {
        $this->pontuacao--;
    }

    public function exibirPost() {
        $conteudoExibido = $this->tipo === 'texto' ? "<p>{$this->conteudo}</p>" : "<img src='{$this->conteudo}' alt='{$this->titulo}' />";
        return "{$conteudoExibido}<p>Pontuação: {$this->pontuacao}</p>";
        //return "<h2>{$this->titulo}</h2>{$conteudoExibido}<p>Pontuação: {$this->pontuacao}</p>";
    }
}
?>
