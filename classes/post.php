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
    //private $conn; // Conexão com o banco de dados

    public function __construct($id, $title, $content, $author, $tags = [], $image = null/*, $conn*/ ) {
        $this->id = $id;
        $this->title = $title;
        $this->content = $content;
        $this->author = $author;
        $this->tags = $tags;
        $this->score = 0; // Inicialmente a pontuação começa em 0
        $this->comments = [];
        $this->image = $image;
        //$this->conn = $conn;
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

    /*
    função temporaria até conexão com banco de dados
    sim eu sei que um usuario atualmente pode spamar o like
    */

    public function displayScore() {
        echo "<div>";
        echo "<p>Pontuação: {$this->score}</p>";
        echo "<button onclick='likePost({$this->id})'>👍</button>";
        echo "<button onclick='dislikePost({$this->id})'>👎</button>";
        echo "</div>";
    }


    //para futura conexção com banco de dados, apague a função anteriro quando estiver pronto

    /*public function displayScore($userId, $postId, $conn) {
        $vote = getUserVote($userId, $postId, $conn);
        echo "<div>";
        echo "<p>Pontuação: {$this->score}</p>";
        if ($vote['vote'] === 'like') {
            echo "<button style='border: 2px solid green;' onclick='removeVote($postId)'>👍</button>";
            echo "<button onclick='dislikePost($postId)'>👎</button>";
        } elseif ($vote['vote'] === 'dislike') {
            echo "<button onclick='likePost($postId)'>👍</button>";
            echo "<button style='border: 2px solid red;' onclick='removeVote($postId)'>👎</button>";
        } else {
            echo "<button onclick='likePost($postId)'>👍</button>";
            echo "<button onclick='dislikePost($postId)'>👎</button>";
        }
        echo "</div>";
    }*/
    
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