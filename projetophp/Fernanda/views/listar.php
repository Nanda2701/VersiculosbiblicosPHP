<?php $versiculos = $versiculos ?? []; ?>
<?php $usuarioAtual = is_array($usuarioAtual ?? null) ? $usuarioAtual : null; ?>
<!doctype html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Versículos — Listar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<nav class="navbar bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="index.php">Versículos Bíblicos</a>
        <div class="d-flex gap-2">
            <?php if ($usuarioAtual): ?>
                <a class="btn btn-success" href="index.php?pagina=inserir">Adicionar</a>
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
    <h1 class="mb-4">Versículos</h1>

    <div class="table-responsive verse-table-wrap">
        <table class="table table-striped table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Texto</th>
                    <th>Data de inserção</th>
                    <th>Foto</th>
                    <th class="text-center">Ações</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($versiculos)): ?>
                <tr><td colspan="4" class="text-center py-4">Ainda não existem registos.</td></tr>
            <?php else: ?>
                <?php foreach ($versiculos as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['versiculo'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($item['criado_em'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></td>
                        <td>
                            <?php if (!empty($item['foto'])): ?>
                                <img
                                    src="foto/<?= htmlspecialchars(rawurlencode($item['foto']), ENT_QUOTES, 'UTF-8') ?>"
                                    alt="Foto do versículo"
                                    class="list-photo"
                                >
                            <?php else: ?>
                                —
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="d-flex flex-column gap-2 align-items-stretch action-stack">
                                <a class="btn btn-sm btn-primary"
                                   href="index.php?pagina=detalhe&id=<?= (int) $item['id'] ?>">
                                    Ver detalhe
                                </a>
                                <?php if ($usuarioAtual && (int) ($item['usuario_id'] ?? 0) === (int) $usuarioAtual['id']): ?>
                                    <a class="btn btn-sm btn-warning"
                                       href="index.php?pagina=editar&id=<?= (int) $item['id'] ?>">
                                        Editar
                                    </a>
                                    <form method="post" action="index.php?pagina=apagar" class="m-0">
                                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
                                        <input type="hidden" name="id" value="<?= (int) $item['id'] ?>">
                                        <button class="btn btn-sm btn-danger w-100" type="submit" onclick="return confirm('Tem a certeza que pretende apagar este versículo?');">Apagar</button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
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