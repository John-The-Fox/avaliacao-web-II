<?php

class Post {
    private $id;
    private $titulo;
    private $conteudo;
    private $tipo;
    private $autor; // ID do autor
    private $pontuacao;

    public function __construct($id, $titulo, $conteudo, $tipo, $autor, $pontuacao = 0) {
        $this->id = $id;
        $this->titulo = $titulo;
        $this->conteudo = $conteudo;
        $this->tipo = $tipo;
        $this->autor = $autor;
        $this->pontuacao = $pontuacao;
    }

     // Métodos Getters
     public function getId() { return $this->id; }
     public function getTitulo() { return $this->titulo; }
     public function getConteudo() { return $this->conteudo; }
     public function getTipo() { return $this->tipo; }
     public function getAutor() { return $this->autor; }
     public function getPontuacao() { return $this->pontuacao; }

     // Atualiza título ou conteúdo
     public function atualizar($titulo, $conteudo) {
        global $mysqli;
        $stmt = $mysqli->prepare("UPDATE posts SET titulo = ?, conteudo = ? WHERE id = ?");
        $stmt->bind_param("ssi", $titulo, $conteudo, $this->id);
        $stmt->execute();
    }

    // Remover post (somente pelo autor)
    public function remover() {
        global $mysqli;
        $stmt = $mysqli->prepare("DELETE FROM posts WHERE id = ?");
        $stmt->bind_param("i", $this->id);
        $stmt->execute();
    }

    function atualizarPontuacoes($mysqli) {
        $query = "
            UPDATE posts p
            SET p.pontuacao = (
                SELECT 
                    COALESCE(SUM(CASE 
                        WHEN v.tipo = 'like' THEN 1 
                        WHEN v.tipo = 'dislike' THEN -1 
                        ELSE 0 
                    END), 0)
                FROM votos v
                WHERE v.post_id = p.id
            );
        ";
    
        if ($mysqli->query($query) === TRUE) {
            return true;
        } else {
            echo "Erro ao atualizar pontuações: " . $mysqli->error;
            return false;
        }
    }

    public function exibirPost() {
        $conteudoExibido = $this->tipo === 'texto' ? "<p>{$this->conteudo}</p>" : "<img src='{$this->conteudo}' alt='{$this->titulo}' />";
        return "{$conteudoExibido}<p>Pontuação: {$this->pontuacao}</p>";
	}

    // Curtir post
    public function curtir() {
        global $mysqli;

        $usuarioId = $_SESSION['usuario']->getId();
        $id = $this->id;

        // Verifica se já existe um voto
        $stmt = $mysqli->prepare("SELECT tipo FROM votos WHERE post_id = ? AND usuario_id = ?");
        $stmt->bind_param("ii", $id, $usuarioId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($voto = $result->fetch_assoc()) {
            if ($voto['tipo'] !== 'like') {
                // Atualiza para "like"
                $stmt = $mysqli->prepare("UPDATE votos SET tipo = 'like' WHERE post_id = ? AND usuario_id = ?");
                $stmt->bind_param("ii", $id, $usuarioId);
                $stmt->execute();
            } else {
                // Remove voto
                $stmt = $mysqli->prepare("DELETE FROM votos WHERE post_id = ? AND usuario_id = ?");
                $stmt->bind_param("ii", $id, $usuarioId);
                $stmt->execute();
            }
        } else {
            // Novo voto
            //echo "LIKE";
            $stmt = $mysqli->prepare("INSERT INTO votos (post_id, usuario_id, tipo) VALUES (?, ?, 'like')");
            $stmt->bind_param("ii", $id, $usuarioId);
            $stmt->execute();
        }
        $this->atualizarPontuacoes($mysqli); // Atualiza pontuações do post
    }

    // Descurtir post
    public function descurtir() {
        global $mysqli;

        $usuarioId = $_SESSION['usuario']->getId();
        $id = $this->id;

        // Verifica se já existe um voto
        $stmt = $mysqli->prepare("SELECT tipo FROM votos WHERE post_id = ? AND usuario_id = ?");
        $stmt->bind_param("ii", $id, $usuarioId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($voto = $result->fetch_assoc()) {
            if ($voto['tipo'] !== 'dislike') {
                // Atualiza para "dislike"
                $stmt = $mysqli->prepare("UPDATE votos SET tipo = 'dislike' WHERE post_id = ? AND usuario_id = ?");
                $stmt->bind_param("ii", $id, $usuarioId);
                $stmt->execute();
            } else {
                // Remove voto
                $stmt = $mysqli->prepare("DELETE FROM votos WHERE post_id = ? AND usuario_id = ?");
                $stmt->bind_param("ii", $id, $usuarioId);
                $stmt->execute();
            }
        } else {
            // Novo voto
            $stmt = $mysqli->prepare("INSERT INTO votos (post_id, usuario_id, tipo) VALUES (?, ?, 'dislike')");
            $stmt->bind_param("ii", $id, $usuarioId);
            $stmt->execute();
        }
        $this->atualizarPontuacoes($mysqli); // Atualiza pontuações do post
    }
}
?>
