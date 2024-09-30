<?php
class Comment {
    private $id;
    private $texto;
    private $autor;

    public function __construct($id, $texto, $autor) {
        $this->id = $id;
        $this->texto = $texto;
        $this->autor = $autor;
    }

    public function getId() {
        return $this->id;
    }

    public function getTexto() {
        return $this->texto;
    }

    public function getAutor() {
        return $this->autor;
    }
}

?>
