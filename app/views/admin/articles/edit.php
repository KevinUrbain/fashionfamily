<div class="layout">
    <aside class="sidebar">
        <div class="logo">
            <img src="<?= BASE_URL ?>/img/logo_footer.png" alt="Logo" />
            <h1>Admin</h1>
        </div>
        <div class="sidebar_nav">
            <nav>
                <ul>
                    <li><img src="<?= BASE_URL ?>/img/img-dash/icone_dash.png" alt="" />
                        <a href="<?= BASE_URL ?>/admin">Dashboard</a></li>
                    <li><img src="<?= BASE_URL ?>/img/img-dash/icone_produits.png" alt="" />
                        <a href="<?= BASE_URL ?>/admin">Products</a></li>
                </ul>
            </nav>
        </div>
    </aside>

    <div class="main">
        <header class="header">
            <h1>Admin &gt; Modifier article</h1>
            <a href="<?= BASE_URL ?>/logout">
                <img src="<?= BASE_URL ?>/img/img-dash/logout.png" alt="Déconnexion" />
            </a>
        </header>

        <div class="content">
            <div class="edit-user-wrapper">
                <h2>Modifier l'article</h2>

                <?php if (!empty($errors)): ?>
                    <ul class="form-errors">
                        <?php foreach ($errors as $error): ?>
                            <li><?= escape($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>

                <form method="POST" action="<?= BASE_URL ?>/admin/articles/edit">
                    <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                    <input type="hidden" name="id" value="<?= (int) $article['id'] ?>">

                    <label for="title">Titre</label>
                    <input type="text" id="title" name="title" value="<?= escape($article['title']) ?>" required>

                    <label for="description">Description</label>
                    <textarea id="description" name="description" rows="4"><?= escape($article['description']) ?></textarea>

                    <label for="price">Prix (€)</label>
                    <input type="number" id="price" name="price" step="0.01" min="0"
                        value="<?= escape($article['price']) ?>" required>

                    <label for="quantity">Quantité</label>
                    <input type="number" id="quantity" name="quantity" min="0"
                        value="<?= escape($article['quantity']) ?>">

                    <label for="article_condition">Condition</label>
                    <select id="article_condition" name="article_condition">
                        <?php foreach (['new' => 'Neuf', 'like_new' => 'Comme neuf', 'good' => 'Bon état', 'fair' => 'Correct', 'poor' => 'Usé'] as $val => $label): ?>
                            <option value="<?= $val ?>" <?= $article['article_condition'] === $val ? 'selected' : '' ?>><?= $label ?></option>
                        <?php endforeach; ?>
                    </select>

                    <label for="status">Statut</label>
                    <select id="status" name="status">
                        <option value="active" <?= $article['status'] === 'active' ? 'selected' : '' ?>>Actif</option>
                        <option value="inactive" <?= $article['status'] === 'inactive' ? 'selected' : '' ?>>Inactif</option>
                    </select>

                    <div class="form-actions">
                        <button type="submit">Enregistrer</button>
                        <a href="<?= BASE_URL ?>/admin">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
