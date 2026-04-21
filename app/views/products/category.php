<section class="search-results">

    <div class="search-results__header">
        <h1 class="search-results__title"><?= escape($label) ?></h1>
        <span class="search-results__count">
            <?= count($articles) ?> article<?= count($articles) > 1 ? 's' : '' ?>
        </span>
    </div>

    <?php if (empty($articles)): ?>
        <div class="search-results__empty">
            <svg xmlns="http://www.w3.org/2000/svg" width="52" height="52" viewBox="0 0 24 24"
                 fill="none" stroke="#d1d5db" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/>
                <line x1="7" y1="7" x2="7.01" y2="7"/>
            </svg>
            <p>Aucun article dans la catégorie <strong><?= escape($label) ?></strong>.</p>
            <a href="<?= BASE_URL ?>/products" class="search-results__browse">Voir tous les articles</a>
        </div>

    <?php else: ?>
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
                            <input type="hidden" name="redirect" value="/products/category?cat=<?= urlencode($slug) ?>">
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
