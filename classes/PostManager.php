<?php
class PostManager {

    //solução temporaria
    private $posts = [];
    
    /* para futura conexão com banco de dados */
    /*private $conn; // Conexão com o banco de dados

    public function __construct($conn) {
        $this->conn = $conn;
    }*/

    // essas funções precisam de ajustes para futuro banco de dados
    public function createPost($id, $title, $content, $author, $tags = [], $image = null) {
        $post = new Post($id, $title, $content, $author, $tags, $image);
        $this->posts[$id] = $post;
    }

    public function deletePost($id) {
        if (isset($this->posts[$id])) {
            unset($this->posts[$id]);
        }
    }

    public function updatePost($id, $title, $content, $tags = [], $image = null) {
        if (isset($this->posts[$id])) {
            $this->posts[$id] = new Post($id, $title, $content, $this->posts[$id]->author, $tags, $image);
        }
    }

    public function displayAllPosts() {
        foreach ($this->posts as $post) {
            $post->displayPost();
        }
    }

    /*
    function getUserVote($userId, $postId, $conn) {
        $sql = "SELECT vote FROM user_likes_dislikes WHERE user_id = ? AND post_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $userId, $postId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc(); // Retorna o tipo de voto se houver
    }
    
    // Função para dar like
    public function likePost($userId, $postId) {
        $vote = $this->getUserVote($userId, $postId);

        if ($vote) {
            if ($vote['vote'] !== 'like') {
                $sql = "UPDATE user_likes_dislikes SET vote = 'like' WHERE user_id = ? AND post_id = ?";
                $stmt = $this->conn->prepare($sql);
                $stmt->bind_param("ii", $userId, $postId);
                $stmt->execute();
            }
        } else {
            $sql = "INSERT INTO user_likes_dislikes (user_id, post_id, vote) VALUES (?, ?, 'like')";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ii", $userId, $postId);
            $stmt->execute();
        }
    }

    // Função para dar dislike
    public function dislikePost($userId, $postId) {
        $vote = $this->getUserVote($userId, $postId);

        if ($vote) {
            if ($vote['vote'] !== 'dislike') {
                $sql = "UPDATE user_likes_dislikes SET vote = 'dislike' WHERE user_id = ? AND post_id = ?";
                $stmt = $this->conn->prepare($sql);
                $stmt->bind_param("ii", $userId, $postId);
                $stmt->execute();
            }
        } else {
            $sql = "INSERT INTO user_likes_dislikes (user_id, post_id, vote) VALUES (?, ?, 'dislike')";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ii", $userId, $postId);
            $stmt->execute();
        }
    }

    // Função para remover o voto
    public function removeVote($userId, $postId) {
        $sql = "DELETE FROM user_likes_dislikes WHERE user_id = ? AND post_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $userId, $postId);
        $stmt->execute();
    }
    */
}