<?php

class Usuario
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function buscarPorEmail(string $email): ?array
    {
        $stmt = $this->pdo->prepare(
            'SELECT id, nome, email, senha
             FROM usuario
             WHERE email = :email
             LIMIT 1'
        );
        $stmt->execute(['email' => $email]);
        $usuario = $stmt->fetch();

        return $usuario ?: null;
    }

    public function criar(string $nome, string $email, string $senha): int
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO usuario (nome, email, senha)
             VALUES (:nome, :email, :senha)'
        );
        $stmt->execute([
            'nome'  => $nome,
            'email' => $email,
            'senha' => password_hash($senha, PASSWORD_DEFAULT),
        ]);

        return (int) $this->pdo->lastInsertId();
    }
}
