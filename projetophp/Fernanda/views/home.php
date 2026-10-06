<?php
$versiculos = is_array($versiculos ?? null) ? $versiculos : [];
$destaque = $versiculos[0] ?? null;
$recentes = array_slice($versiculos, 1);
$usuarioAtual = is_array($usuarioAtual ?? null) ? $usuarioAtual : null;
?>
<!doctype html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Versículos Bíblicos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<nav class="navbar navbar-expand bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="index.php">Versículos Bíblicos</a>
        <div class="d-flex align-items-center gap-2">
            <a class="btn btn-outline-primary" href="index.php?pagina=listar">Ver todos</a>
            <?php if ($usuarioAtual): ?>
                <span class="d-none d-md-inline text-muted">Olá, <?= htmlspecialchars($usuarioAtual['nome'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></span>
                <form method="post" action="index.php?pagina=sair" class="m-0">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
                    <button class="btn btn-outline-secondary" type="submit">Sair</button>
                </form>
            <?php else: ?>
                <a class="btn btn-outline-primary" href="index.php?pagina=login">Entrar</a>
                <a class="btn btn-primary" href="index.php?pagina=cadastro">Criar conta</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<main class="container py-5">
    <?php if ($destaque): ?>
        <section class="home-card mx-auto text-center mb-5">
            <?php if (!empty($destaque['foto'])): ?>
                <div class="photo-frame mb-4">
                    <img src="foto/<?= htmlspecialchars(rawurlencode($destaque['foto']), ENT_QUOTES, 'UTF-8') ?>"
                         class="featured-photo" alt="Imagem do versículo em destaque">
                </div>
            <?php endif; ?>
            <p class="text-uppercase text-muted small fw-bold mb-2">Versículo em destaque</p>
            <h1 class="mb-3">Uma palavra para o seu dia</h1>
            <blockquote class="featured-verse mb-4">
                “<?= nl2br(htmlspecialchars($destaque['versiculo'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')) ?>”
            </blockquote>
            <p class="lead mb-4">Leia e partilhe palavras de inspiração.</p>
            <div class="d-flex justify-content-center flex-wrap gap-2 mb-3">
                <a class="btn btn-ler btn-lg" href="index.php?pagina=detalhe&amp;id=<?= (int) $destaque['id'] ?>">Ler versículo</a>
                <a class="btn btn-adicionar btn-lg" href="index.php?pagina=inserir">Publicar versículo</a>
            </div>
            <?php if ($usuarioAtual && (int) ($destaque['usuario_id'] ?? 0) === (int) $usuarioAtual['id']): ?>
                <div class="d-flex justify-content-center flex-wrap gap-2">
                    <a class="btn btn-primary" href="index.php?pagina=editar&id=<?= (int) $destaque['id'] ?>">Editar</a>
                    <form method="post" action="index.php?pagina=apagar" class="m-0">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
                        <input type="hidden" name="id" value="<?= (int) $destaque['id'] ?>">
                        <button class="btn btn-danger" type="submit" onclick="return confirm('Tem a certeza que pretende apagar este versículo?');">Apagar</button>
                    </form>
                </div>
            <?php endif; ?>
        </section>
    <?php else: ?>
        <section class="home-card mx-auto text-center mb-5">
            <h1 class="mb-3">Versículos Bíblicos</h1>
            <p class="lead mb-4">Leia e partilhe palavras de inspiração.</p>
            <a class="btn btn-adicionar btn-lg" href="index.php?pagina=inserir">Publicar o primeiro versículo</a>
        </section>
    <?php endif; ?>

    <?php if ($recentes): ?>
        <section aria-labelledby="publicacoes-recentes">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                <h2 id="publicacoes-recentes" class="h3 mb-0">Mais publicações</h2>
                <a class="btn btn-outline-primary" href="index.php?pagina=listar">Ver todas</a>
            </div>
            <div class="row g-4">
                <?php foreach ($recentes as $item): ?>
                    <div class="col-12 col-md-6">
                        <article class="publication-card h-100">
                            <?php if (!empty($item['foto'])): ?>
                                <img src="foto/<?= htmlspecialchars(rawurlencode($item['foto']), ENT_QUOTES, 'UTF-8') ?>"
                                     class="publication-photo" alt="Imagem da publicação">
                            <?php endif; ?>
                            <div class="p-4">
                                <p class="publication-verse mb-3">
                                    “<?= nl2br(htmlspecialchars($item['versiculo'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')) ?>”
                                </p>
                                <p class="text-muted small">
                                    <?= htmlspecialchars($item['criado_em'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>
                                </p>
                                <div class="d-flex flex-wrap gap-2">
                                    <a class="btn btn-sm btn-outline-primary"
                                       href="index.php?pagina=detalhe&amp;id=<?= (int) $item['id'] ?>">
                                        Ler publicação
                                    </a>
                                    <?php if ($usuarioAtual && (int) ($item['usuario_id'] ?? 0) === (int) $usuarioAtual['id']): ?>
                                        <a class="btn btn-sm btn-warning"
                                           href="index.php?pagina=editar&id=<?= (int) $item['id'] ?>">
                                            Editar
                                        </a>
                                        <form method="post" action="index.php?pagina=apagar" class="m-0">
                                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
                                            <input type="hidden" name="id" value="<?= (int) $item['id'] ?>">
                                            <button class="btn btn-sm btn-danger" type="submit" onclick="return confirm('Tem a certeza que pretende apagar este versículo?');">Apagar</button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </article>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
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