<?php $dados = $dados ?? []; ?>
<?php $usuarioAtual = is_array($usuarioAtual ?? null) ? $usuarioAtual : null; ?>
<!doctype html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Adicionar versículo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<nav class="navbar bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="index.php">Versículos Bíblicos</a>
        <div class="d-flex align-items-center gap-2">
            <a class="btn btn-outline-primary" href="index.php?pagina=listar">Ver lista</a>
            <?php if ($usuarioAtual): ?>
                <span class="d-none d-md-inline text-muted">Olá, <?= htmlspecialchars($usuarioAtual['nome'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></span>
                <form method="post" action="index.php?pagina=sair" class="m-0">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
                    <button class="btn btn-outline-secondary" type="submit">Sair</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</nav>

<main class="container py-5">
    <section class="form-card mx-auto">
        <div>
            <h1 class="h3 mb-4">Adicionar versículo</h1>

            <?php if (!empty($erro)): ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars($erro, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>
                </div>
            <?php endif; ?>
            <p class="text-muted">A publicar como <?= htmlspecialchars($usuarioAtual['nome'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>.</p>
            <form method="post" action="index.php?pagina=inserir" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
                <div class="mb-3">
                    <label for="versiculo" class="form-label">Texto ou explicação do versículo</label>
                    <textarea
                        class="form-control"
                        id="versiculo"
                        name="versiculo"
                        rows="4"
                        required
                    ><?= htmlspecialchars(is_string($dados['versiculo'] ?? null) ? $dados['versiculo'] : '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></textarea>
                    <div class="form-text">Pode adicionar uma explicação longa. Limite do campo na base de dados: 65.535 bytes.</div>
                </div>

                <div class="mb-3">
                    <label for="foto" class="form-label">Foto</label>
                    <input
                        type="file"
                        class="form-control"
                        id="foto"
                        name="foto"
                        accept="image/jpeg,image/png,image/gif,image/webp"
                        required
                    >
                    <div class="form-text">Formatos: JPG, PNG, GIF ou WebP. Máximo: 5 MB.</div>
                </div>

                <button type="submit" class="btn btn-adicionar">Guardar</button>
                <a href="index.php" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </section>
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