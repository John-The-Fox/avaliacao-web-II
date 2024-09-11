<?php
class User {
    private $username;
    private $email;
    private $password;
    
    public function __construct($username, $email, $password) {
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
    }

    public function displayProfile() {
        echo "<p>Usuário: {$this->username}</p>";
        echo "<p>Email: {$this->email}</p>";
    }
    
    // Métodos de validação e manipulação do usuário
    public function validatePassword($passwordInput) {
        return $this->password === $passwordInput;
    }
}