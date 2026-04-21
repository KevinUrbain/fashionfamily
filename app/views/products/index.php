<?php $successMsg = getFlashMessage('success'); ?>
<?php if ($successMsg): ?>
    <p style="color: green; text-align: center; padding: 0.5rem;"><?= escape($successMsg) ?></p>
<?php endif; ?>
<?php $errorMsg = getFlashMessage('error'); ?>
<?php if ($errorMsg): ?>
    <p style="color: red; text-align: center; padding: 0.5rem;"><?= escape($errorMsg) ?></p>
<?php endif; ?>

<section class="best-selling">
    <div class="products-toolbar">
        <h1>Tous nos articles</h1>
        <form class="products-sort" method="GET" action="<?= BASE_URL ?>/products">
            <label for="sort-select">Trier par</label>
            <select id="sort-select" name="sort" onchange="this.form.submit()">
                <option value="newest"     <?= ($sort ?? 'newest') === 'newest'     ? 'selected' : '' ?>>Plus récents</option>
                <option value="oldest"     <?= ($sort ?? '') === 'oldest'     ? 'selected' : '' ?>>Plus anciens</option>
                <option value="price_asc"  <?= ($sort ?? '') === 'price_asc'  ? 'selected' : '' ?>>Prix croissant</option>
                <option value="price_desc" <?= ($sort ?? '') === 'price_desc' ? 'selected' : '' ?>>Prix décroissant</option>
            </select>
        </form>
    </div>
    <div class="best_selling_container">
        <?php if (!empty($articles)): ?>
            <?php foreach ($articles as $a): ?>
                <div class="best_selling_card">
                    <div class="best_selling_img">
                        <img src="<?= BASE_URL . escape($a['image_path']) ?>" alt="<?= escape($a['title']) ?>" />
                    </div>
                    <p><?= escape($a['title']) ?></p>
                    <div class="stock">
                        <span>En stock : <?= (int) $a['quantity'] ?></span>
                        <span>€<?= number_format((float) $a['price'], 2) ?></span>
                    </div>
                    <a href="<?= BASE_URL ?>/products/show?id=<?= $a['id'] ?>" class="btn-voir-plus">Voir plus</a>
                    <?php if ((int) $a['quantity'] > 0): ?>
                        <form action="<?= BASE_URL ?>/cart/add" method="POST">
                            <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                            <input type="hidden" name="article_id" value="<?= (int) $a['id'] ?>">
                            <input type="hidden" name="quantity" value="1">
                            <input type="hidden" name="redirect" value="/products">
                            <button type="submit" class="btn-panier">Ajouter au panier</button>
                        </form>
                    <?php else: ?>
                        <span class="rupture-stock">Rupture de stock</span>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aucun article disponible pour le moment.</p>
        <?php endif; ?>
    </div>
</section>
