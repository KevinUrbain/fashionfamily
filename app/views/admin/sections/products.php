<div class="section-header">
    <h2>Produits : <?= count($articles) ?></h2>
    <div class="search-bar">
        <input type="text" id="search-input" value="<?= escape($search ?? '') ?>" placeholder="Rechercher par titre ou description…" autocomplete="off" />
        <button type="button" id="search-btn">Rechercher</button>
        <?php if (!empty($search)): ?>
            <button type="button" id="search-reset-btn">Réinitialiser</button>
        <?php endif; ?>
    </div>
</div>

<?php if (empty($articles)): ?>
    <p class="no-results">Aucun article trouvé<?= !empty($search) ? ' pour « ' . escape($search) . ' »' : '' ?>.</p>
<?php else: ?>
<table class="admin-table">
    <thead>
        <tr>
            <th>Id</th>
            <th>Titre</th>
            <th>Description</th>
            <th>Prix</th>
            <th>Quantité</th>
            <th>Condition</th>
            <th>Statut</th>
            <th>Date d'ajout</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($articles as $article): ?>
            <tr>
                <td><?= (int) $article['id'] ?></td>
                <td><?= escape($article['title']) ?></td>
                <td><?= escape($article['description']) ?></td>
                <td><?= escape($article['price']) ?>€</td>
                <td><?= (int) $article['quantity'] ?></td>
                <td><?= escape($article['article_condition']) ?></td>
                <td><?= escape($article['status']) ?></td>
                <td><?= escape($article['created_at']) ?></td>
                <td class="table-actions">
                    <a class="btn-edit" href="<?= BASE_URL ?>/admin/articles/edit?id=<?= $article['id'] ?>">Modifier</a>
                    <form method="POST" action="<?= BASE_URL ?>/admin/articles/delete"
                        onsubmit="return confirm('Supprimer cet article ?')">
                        <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                        <input type="hidden" name="id" value="<?= $article['id'] ?>">
                        <button type="submit" class="btn-delete">Supprimer</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>