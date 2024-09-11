<?php
class Post {
    private $id;
    private $title;
    private $content;
    private $author;
    private $tags;
    private $score;
    private $comments;
    private $image;

    public function __construct($id, $title, $content, $author, $tags = [], $image = null) {
        $this->id = $id;
        $this->title = $title;
        $this->content = $content;
        $this->author = $author;
        $this->tags = $tags;
        $this->score = 0; // Inicialmente a pontuação começa em 0
        $this->comments = [];
        $this->image = $image;
    }

    // Métodos para exibir os dados do post no frontend
    public function displayPost() {
        echo "<h2>{$this->title}</h2>";
        if ($this->image) {
            echo "<img src='{$this->image}' alt='Imagem do Post'>";
        }
        echo "<p>{$this->content}</p>";
        echo "<p><strong>Autor:</strong> {$this->author}</p>";
        echo "<p><strong>Tags:</strong> " . implode(', ', $this->tags) . "</p>";
        $this->displayScore();
        $this->displayComments();
    }

    public function displayScore() {
        echo "<div>";
        echo "<p>Pontuação: {$this->score}</p>";
        echo "<button onclick='likePost({$this->id})'>👍</button>";
        echo "<button onclick='dislikePost({$this->id})'>👎</button>";
        echo "</div>";
    }

    public function displayComments() {
        echo "<h3>Comentários</h3>";
        foreach ($this->comments as $comment) {
            echo "<p>{$comment}</p>";
        }
        echo "<textarea placeholder='Digite seu comentário'></textarea>";
        echo "<button>Enviar Comentário</button>";
    }

    // Métodos de manipulação de dados
    public function addComment($comment) {
        $this->comments[] = $comment;
    }

    public function like() {
        $this->score++;
    }

    public function dislike() {
        $this->score--;
    }
}