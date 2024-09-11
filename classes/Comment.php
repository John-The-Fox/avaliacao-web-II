<?php
class Comment {
    private $author;
    private $content;
    private $date;

    public function __construct($author, $content) {
        $this->author = $author;
        $this->content = $content;
        $this->date = date('Y-m-d H:i:s');
    }

    public function displayComment() {
        echo "<div>";
        echo "<p><strong>{$this->author}:</strong> {$this->content}</p>";
        echo "<p><small>{$this->date}</small></p>";
        echo "</div>";
    }
}