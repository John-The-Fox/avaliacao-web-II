<?php
class PostManager {
    private $posts = [];

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
}