<?php

class Versiculo
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function listar(): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT id, usuario_id, versiculo, foto, criado_em, atualizado_em
             FROM versiculo
             ORDER BY criado_em DESC'
        );
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function maisRecente(): ?array
    {
        $stmt = $this->pdo->prepare(
            'SELECT id, usuario_id, versiculo, foto, criado_em, atualizado_em
             FROM versiculo
             ORDER BY criado_em DESC, id DESC
             LIMIT 1'
        );
        $stmt->execute();
        $registo = $stmt->fetch();

        return $registo ?: null;
    }

    public function maisRecentes(int $limite = 5): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT id, usuario_id, versiculo, foto, criado_em, atualizado_em
             FROM versiculo
             ORDER BY criado_em DESC, id DESC
             LIMIT :limite'
        );
        $stmt->bindValue(':limite', max(1, $limite), PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function buscarPorId(int $id): ?array
    {
        $stmt = $this->pdo->prepare(
            'SELECT id, usuario_id, versiculo, foto, criado_em, atualizado_em
             FROM versiculo
             WHERE id = :id'
        );
        $stmt->execute(['id' => $id]);

        $registo = $stmt->fetch();

        return $registo ?: null;
    }

    public function criar(int $usuarioId, string $texto, string $foto): int
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO versiculo (usuario_id, versiculo, foto, criado_em, atualizado_em)
             VALUES (:usuario_id, :versiculo, :foto, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)'
        );

        $stmt->execute([
            'usuario_id' => $usuarioId,
            'versiculo'  => $texto,
            'foto'       => $foto,
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    public function atualizar(int $id, int $usuarioId, string $texto, ?string $foto = null): bool
    {
        if ($foto === null) {
            $stmt = $this->pdo->prepare(
                'UPDATE versiculo
                 SET versiculo = :versiculo,
                     atualizado_em = CURRENT_TIMESTAMP
                 WHERE id = :id AND usuario_id = :usuario_id'
            );

            $stmt->execute([
                'id' => $id,
                'usuario_id' => $usuarioId,
                'versiculo' => $texto,
            ]);

            return $stmt->rowCount() > 0;
        }

        $stmt = $this->pdo->prepare(
            'UPDATE versiculo
             SET versiculo = :versiculo,
                 foto = :foto,
                 atualizado_em = CURRENT_TIMESTAMP
             WHERE id = :id AND usuario_id = :usuario_id'
        );

        $stmt->execute([
            'id' => $id,
            'usuario_id' => $usuarioId,
            'versiculo' => $texto,
            'foto' => $foto,
        ]);

        return $stmt->rowCount() > 0;
    }

    public function apagar(int $id, int $usuarioId): bool
    {
        $stmt = $this->pdo->prepare(
            'DELETE FROM versiculo
             WHERE id = :id AND usuario_id = :usuario_id'
        );

        $stmt->execute([
            'id' => $id,
            'usuario_id' => $usuarioId,
        ]);

        return $stmt->rowCount() > 0;
    }

}