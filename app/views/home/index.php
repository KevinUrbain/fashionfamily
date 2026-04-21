<section class="hero">
    <div class="hero_container">
        <div class="hero_title">
            <h1>Achetez et vendez des articles partout dans le monde</h1>
            <p>Découvrez tous nos produits disponibles en ligne</p>
            <a href="<?= BASE_URL ?>/products?sort=newest" class="hero-btn">Voir nos produits &rarr;</a>
        </div>
        <div class="hero_image">
            <img src="<?= BASE_URL ?>/img/Hero Image.png"
                alt="Modèle masculin portant un tshirt de la nouvelle collection" />
            <img src="<?= BASE_URL ?>/img/Burst-pucker.png" alt="" aria-hidden="true" />
        </div>
    </div>
</section>

<section class="feature">
    <div class="feature_container">
        <div class="feature_cart">
            <div class="feature_img">
                <img src="<?= BASE_URL ?>/img/camion.png" aria-hidden="true" alt="">
            </div>
            <h3>Livraison gratuite</h3>
            <p>Livraison gratuite sur toutes vos commandes.</p>
        </div>
        <div class="feature_cart">
            <div class="feature_img">
                <img src="<?= BASE_URL ?>/img/Badge.png" aria-hidden="true" alt="">
            </div>
            <h3>Qualité garantie</h3>
            <p>Des articles vérifiés par notre équipe.</p>
        </div>
        <div class="feature_cart">
            <div class="feature_img">
                <img src="<?= BASE_URL ?>/img/Bouclier.png" aria-hidden="true" alt="">
            </div>
            <h3>Paiement sécurisé</h3>
            <p>Vos transactions sont protégées.</p>
        </div>
    </div>
</section>

<?php $successMsg = getFlashMessage('success'); ?>
<?php if ($successMsg): ?>
    <p style="color: green; text-align: center; padding: 0.5rem;"><?= escape($successMsg) ?></p>
<?php endif; ?>

<section class="best-selling">
    <h3>Les articles les plus récents</h3>
    <div class="best_selling_container">
        <?php if (!empty($articlesByDateDesc)): ?>
            <?php foreach ($articlesByDateDesc as $a): ?>
                <div class="best_selling_card">
                    <div class="best_selling_img">
                        <img src="<?= BASE_URL . escape($a['image_path']) ?>" alt="<?= escape($a['title']) ?>" />
                    </div>
                    <p class="card-title"><?= escape($a['title']) ?></p>
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
                            <input type="hidden" name="redirect" value="/">
                            <button type="submit" class="btn-panier">Ajouter au panier</button>
                        </form>
                    <?php else: ?>
                        <span class="rupture-stock">Rupture de stock</span>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="text-align: center; width: 100%; padding: 2rem 0;">Aucun article pour le moment. Revenez plus tard !
            </p>
        <?php endif; ?>
    </div>
</section>

<section class="cta">
    <div class="cta_container">
        <div class="content_left">
            <h3>Explorez notre univers mode</h3>
            <p>Plongez dans un monde de style et découvrez notre large sélection de catégories de vêtements.</p>
            <a href="<?= BASE_URL ?>/products">Commencer à explorer</a>
        </div>
        <div class="content_right">
            <img src="<?= BASE_URL ?>/img/Category Image.png" alt="">
        </div>
    </div>
</section>