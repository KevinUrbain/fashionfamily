<?php
$conditionLabels = [
    'new'       => 'Neuf',
    'like_new'  => 'Comme neuf',
    'good'      => 'Bon état',
    'fair'      => 'État correct',
    'poor'      => 'Mauvais état',
];
$conditionBadge = [
    'new'       => 'condition-new',
    'like_new'  => 'condition-like-new',
    'good'      => 'condition-good',
    'fair'      => 'condition-fair',
    'poor'      => 'condition-poor',
];
$rawCondition    = $article['article_condition'] ?? '';
$conditionLabel  = $conditionLabels[$rawCondition]  ?? escape($rawCondition);
$conditionClass  = $conditionBadge[$rawCondition]   ?? '';
?>

<?php $successMsg = getFlashMessage('success'); ?>
<?php if ($successMsg): ?>
    <div class="pd-flash pd-flash--success"><?= escape($successMsg) ?></div>
<?php endif; ?>
<?php $errorMsg = getFlashMessage('error'); ?>
<?php if ($errorMsg): ?>
    <div class="pd-flash pd-flash--error"><?= escape($errorMsg) ?></div>
<?php endif; ?>

<section class="pd-wrapper">
    <div class="pd-container">
        <a class="pd-back" href="<?= BASE_URL ?>/products">&larr; Retour aux articles</a>

        <?php if (!empty($article)): ?>
        <div class="pd-layout">

            <!-- Image -->
            <div class="pd-image-col">
                <div class="pd-image-frame">
                    <img src="<?= BASE_URL . escape($article['image_path']) ?>"
                         alt="<?= escape($article['title']) ?>"
                         class="pd-image">
                </div>
            </div>

            <!-- Infos -->
            <div class="pd-info-col">
                <h1 class="pd-title"><?= escape($article['title']) ?></h1>
                <p class="pd-description"><?= escape($article['description']) ?></p>

                <div class="pd-price"><?= number_format((float) $article['price'], 2) ?>&nbsp;€</div>

                <ul class="pd-meta">
                    <li>
                        <span class="pd-meta-label">Condition</span>
                        <span class="pd-condition-badge <?= $conditionClass ?>"><?= $conditionLabel ?></span>
                    </li>
                    <li>
                        <span class="pd-meta-label">En stock</span>
                        <span class="pd-meta-value"><?= (int) $article['quantity'] ?> article<?= (int) $article['quantity'] > 1 ? 's' : '' ?></span>
                    </li>
                    <li>
                        <span class="pd-meta-label">Vendu par</span>
                        <span class="pd-meta-value"><?= escape($article['seller_name']) ?></span>
                    </li>
                </ul>

                <?php if (Auth::isLoggedIn() && Auth::currentUserId() !== (int) $article['user_id']): ?>
                    <a class="pd-btn-contact"
                       href="<?= BASE_URL ?>/messages/conversation?user=<?= (int) $article['user_id'] ?>&article=<?= (int) $article['id'] ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                        </svg>
                        Contacter le vendeur
                    </a>
                <?php elseif (!Auth::isLoggedIn()): ?>
                    <a class="pd-btn-contact pd-btn-contact--guest"
                       href="<?= BASE_URL ?>/login">
                        Connectez-vous pour contacter le vendeur
                    </a>
                <?php endif; ?>

                <?php if ((int) $article['quantity'] > 0): ?>
                    <form action="<?= BASE_URL ?>/cart/add" method="POST" class="pd-cart-form">
                        <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                        <input type="hidden" name="article_id" value="<?= (int) $article['id'] ?>">
                        <input type="hidden" name="redirect" value="/products/show?id=<?= (int) $article['id'] ?>">
                        <div class="pd-qty-row">
                            <label class="pd-qty-label" for="quantity">Quantité</label>
                            <input type="number" id="quantity" name="quantity"
                                   class="pd-qty-input"
                                   value="1" min="1" max="<?= (int) $article['quantity'] ?>">
                        </div>
                        <button type="submit" class="pd-btn-add">Ajouter au panier</button>
                    </form>
                <?php else: ?>
                    <p class="pd-out-of-stock">Rupture de stock</p>
                <?php endif; ?>

                <a class="pd-cart-link" href="<?= BASE_URL ?>/cart">Voir mon panier</a>

                <a class="pd-btn-report" href="<?= BASE_URL ?>/home/contact?subject=<?= urlencode('Signalement article #' . (int) $article['id']) ?>">
                    Signaler cet article
                </a>
            </div>

        </div>
        <?php endif; ?>
    </div>
</section>
