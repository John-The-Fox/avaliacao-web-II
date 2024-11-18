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

    public function getAutorNome() {
        global $mysqli;

        // Prepara a consulta para buscar o nome do autor pelo ID
        $stmt = $mysqli->prepare("SELECT nome FROM usuarios WHERE id = ?");
        $stmt->bind_param("i", $this->autor);
        $stmt->execute();
        $result = $stmt->get_result();

        // Verifica se encontrou o autor
        if ($row = $result->fetch_assoc()) {
            return $row['nome'];
        } else {
            return "Autor desconhecido";
        }
    }
}

?>
