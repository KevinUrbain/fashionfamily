<section class="search-results">

    <div class="search-results__header">
        <?php if ($query !== ''): ?>
            <h1 class="search-results__title">
                Résultats pour <em>"<?= escape($query) ?>"</em>
            </h1>
            <span class="search-results__count">
                <?= count($articles) ?> article<?= count($articles) > 1 ? 's' : '' ?> trouvé<?= count($articles) > 1 ? 's' : '' ?>
            </span>
        <?php else: ?>
            <h1 class="search-results__title">Recherche</h1>
            <p class="search-results__hint">Saisissez au moins 2 caractères dans la barre de recherche.</p>
        <?php endif; ?>
    </div>

    <?php if ($query !== '' && empty($articles)): ?>
        <div class="search-results__empty">
            <svg xmlns="http://www.w3.org/2000/svg" width="52" height="52" viewBox="0 0 24 24"
                 fill="none" stroke="#d1d5db" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
            <p>Aucun article ne correspond à <strong>"<?= escape($query) ?>"</strong>.</p>
            <a href="<?= BASE_URL ?>/products" class="search-results__browse">Voir tous les articles</a>
        </div>

    <?php elseif (!empty($articles)): ?>
        <div class="best_selling_container">
            <?php foreach ($articles as $a): ?>
                <div class="best_selling_card">
                    <div class="best_selling_img">
                        <img src="<?= BASE_URL . escape($a['image_path']) ?>"
                             alt="<?= escape($a['title']) ?>" />
                    </div>
                    <p><?= escape($a['title']) ?></p>
                    <?php if (!empty($a['seller_name'])): ?>
                        <span class="search-results__seller">par <?= escape($a['seller_name']) ?></span>
                    <?php endif; ?>
                    <div class="stock">
                        <span>En stock : <?= (int) $a['quantity'] ?></span>
                        <span>€<?= number_format((float) $a['price'], 2) ?></span>
                    </div>
                    <a href="<?= BASE_URL ?>/products/show?id=<?= (int) $a['id'] ?>" class="btn-voir-plus">Voir plus</a>
                    <?php if ((int) $a['quantity'] > 0): ?>
                        <form action="<?= BASE_URL ?>/cart/add" method="POST">
                            <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                            <input type="hidden" name="article_id" value="<?= (int) $a['id'] ?>">
                            <input type="hidden" name="quantity" value="1">
                            <input type="hidden" name="redirect" value="/search?q=<?= urlencode($query) ?>">
                            <button type="submit" class="btn-panier">Ajouter au panier</button>
                        </form>
                    <?php else: ?>
                        <span class="rupture-stock">Rupture de stock</span>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</section>
