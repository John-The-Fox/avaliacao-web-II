<?php

class Usuario
{
    private $id; // ID único do usuário
    private $nome; // Nome do usuário
    private $email; // Email do usuário
    private $senha; // Senha (já deve ser armazenada criptografada no banco)

    // Construtor para inicializar o objeto
    public function __construct($id, $nome, $email, $senha)
    {
        $this->id = $id;
        $this->nome = $nome;
        $this->email = $email;
        $this->senha = $senha;
    }

    // Métodos Getters e Setters (mantidos como antes)
    public function getId() { return $this->id; }
    public function getNome() { return $this->nome; }
    public function setNome($nome) { $this->nome = $nome; }
    public function getEmail() { return $this->email; }
    public function setEmail($email) { $this->email = $email; }
    public function getSenha() { return $this->senha; }
    public function setSenha($senha) { $this->senha = $senha; }

    // Método para atualizar no banco de dados
    public function atualizarNoBanco($mysqli)
    {
        $stmt = $mysqli->prepare("UPDATE usuarios SET nome = ?, email = ?, senha = ? WHERE id = ?");
        $stmt->bind_param("sssi", $this->nome, $this->email, $this->senha, $this->id);
        $stmt->execute();
        $stmt->close();
    }


    // Métodos específicos
    

    // Recuperar posts do usuário
    public function getPosts($mysqli)
    {
        $stmt = $mysqli->prepare("SELECT * FROM posts WHERE usuario_id = ?");
        $stmt->bind_param("i", $this->id);
        $stmt->execute();
        $result = $stmt->get_result();

        $posts = [];
        while ($row = $result->fetch_assoc()) {
            $posts[] = $row; // Aqui você pode retornar objetos Post, se tiver uma classe Post
        }

        $stmt->close();
        return $posts;
    }

    // Verificar se o usuário já deu like em um post
    public function jaDeuLike($mysqli, $postId)
    {
        $stmt = $mysqli->prepare("SELECT * FROM votos WHERE usuario_id = ? AND post_id = ? AND tipo = 'like'");
        $stmt->bind_param("ii", $this->id, $postId);
        $stmt->execute();
        $result = $stmt->get_result();

        $jaDeuLike = $result->num_rows > 0;
        $stmt->close();
        return $jaDeuLike;
    }

    // Verificar se o usuário já deu dislike em um post
    public function jaDeuDislike($mysqli, $postId)
    {
        $stmt = $mysqli->prepare("SELECT * FROM votos WHERE usuario_id = ? AND post_id = ? AND tipo = 'dislike'");
        $stmt->bind_param("ii", $this->id, $postId);
        $stmt->execute();
        $result = $stmt->get_result();

        $jaDeuDislike = $result->num_rows > 0;
        $stmt->close();
        return $jaDeuDislike;
    }
}

?>
