<?php $email = is_string($email ?? null) ? $email : ''; ?>
<!doctype html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Entrar — Versículos Bíblicos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<nav class="navbar bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="index.php">Versículos Bíblicos</a>
        <a class="btn btn-outline-primary" href="index.php">Início</a>
    </div>
</nav>
<main class="container py-5">
    <section class="form-card auth-card mx-auto">
        <h1 class="h3 mb-2">Entrar</h1>
        <p class="text-muted mb-4">Entre para publicar os seus versículos.</p>
        <?php if (!empty($erro)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($erro, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></div>
        <?php endif; ?>
        <form method="post" action="index.php?pagina=login">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
            <div class="mb-3">
                <label for="email" class="form-label">E-mail</label>
                <input class="form-control" type="email" id="email" name="email" maxlength="100" required
                       autocomplete="email" value="<?= htmlspecialchars($email, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>">
            </div>
            <div class="mb-3">
                <label for="senha" class="form-label">Senha</label>
                <input class="form-control" type="password" id="senha" name="senha" required autocomplete="current-password">
            </div>
            <button class="btn btn-adicionar" type="submit">Entrar</button>
        </form>
        <p class="mt-4 mb-0">Ainda não tem conta? <a href="index.php?pagina=cadastro">Cadastre-se</a>.</p>
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
