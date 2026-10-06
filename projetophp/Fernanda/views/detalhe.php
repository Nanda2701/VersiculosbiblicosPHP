<?php $versiculo = $versiculo ?? null; ?>
<?php $usuarioAtual = is_array($usuarioAtual ?? null) ? $usuarioAtual : null; ?>
<!doctype html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detalhe do versículo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<nav class="navbar bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="index.php">Versículos Bíblicos</a>
        <div class="d-flex gap-2">
            <a class="btn btn-outline-primary" href="index.php?pagina=listar">Voltar à lista</a>
            <?php if ($usuarioAtual): ?>
                <form method="post" action="index.php?pagina=sair" class="m-0">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
                    <button class="btn btn-outline-secondary" type="submit">Sair</button>
                </form>
            <?php else: ?>
                <a class="btn btn-outline-primary" href="index.php?pagina=login">Entrar</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<main class="container py-5">
    <?php if (!$versiculo): ?>
        <div class="alert alert-warning">Versículo não encontrado.</div>
    <?php else: ?>
        <article class="detail-card mx-auto">
            <?php if (!empty($versiculo['foto'])): ?>
                <img
                    src="foto/<?= htmlspecialchars(rawurlencode($versiculo['foto']), ENT_QUOTES, 'UTF-8') ?>"
                    class="detail-photo"
                    alt="Foto do versículo"
                >
            <?php endif; ?>
            <div class="p-4 p-md-5">
                <h1 class="h3">Versículo</h1>
                <p class="fs-4"><?= nl2br(htmlspecialchars($versiculo['versiculo'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')) ?></p>
                <p class="text-muted mb-0">
                    Inserido em <?= htmlspecialchars($versiculo['criado_em'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>
                </p>
                <?php if ($usuarioAtual && (int) ($versiculo['usuario_id'] ?? 0) === (int) $usuarioAtual['id']): ?>
                    <div class="mt-4 d-flex gap-2 flex-wrap">
                        <a class="btn btn-primary" href="index.php?pagina=editar&id=<?= (int) $versiculo['id'] ?>">Editar</a>
                        <form method="post" action="index.php?pagina=apagar" class="m-0">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
                            <input type="hidden" name="id" value="<?= (int) $versiculo['id'] ?>">
                            <button class="btn btn-danger" type="submit" onclick="return confirm('Tem a certeza que pretende apagar este versículo?');">Apagar</button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
        </article>
    <?php endif; ?>
</main>
<footer class="site-footer">
    <div class="container">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
            <div>
                <strong>Versículos Bíblicos</strong>
                <div class="small">Palavras de esperança para o seu dia.</div>
            </div>
            <div class="small">© <?= date('Y') ?> Versículos Bíblicos</div>
        </div>
    </div>
</footer>
</body>
</html>