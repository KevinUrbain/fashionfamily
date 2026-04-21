<?php $successMsg = getFlashMessage('success'); ?>
<?php if ($successMsg): ?>
    <p style="color: green; text-align: center; padding: 0.5rem;"><?= escape($successMsg) ?></p>
<?php endif; ?>
<?php $errorMsg = getFlashMessage('error'); ?>
<?php if ($errorMsg): ?>
    <p style="color: red; text-align: center; padding: 0.5rem;"><?= escape($errorMsg) ?></p>
<?php endif; ?>

<section class="cart" style="padding: 2rem; max-width: 900px; margin: 0 auto;">
    <h1>Mon panier</h1>

    <?php if (empty($items)): ?>
        <div class="cart-empty">
            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#d1d5db" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
            </svg>
            <p class="cart-empty__title">Votre panier est vide</p>
            <p class="cart-empty__sub">Vous n'avez encore rien ajouté.</p>
            <a class="cart-empty__cta" href="<?= BASE_URL ?>/products">Parcourir les articles</a>
        </div>
    <?php else: ?>

        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th style="text-align: left; padding: 0.75rem; border-bottom: 2px solid #ddd;">Article</th>
                    <th style="text-align: right; padding: 0.75rem; border-bottom: 2px solid #ddd;">Prix unitaire</th>
                    <th style="text-align: center; padding: 0.75rem; border-bottom: 2px solid #ddd;">Quantité</th>
                    <th style="text-align: right; padding: 0.75rem; border-bottom: 2px solid #ddd;">Sous-total</th>
                    <th style="padding: 0.75rem; border-bottom: 2px solid #ddd;"></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 0.75rem;">
                            <div style="display: flex; align-items: center; gap: 1rem;">
                                <img src="<?= BASE_URL . escape($item['image_path']) ?>"
                                     alt="<?= escape($item['title']) ?>"
                                     style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;">
                                <a href="<?= BASE_URL ?>/products/show?id=<?= (int) $item['id'] ?>">
                                    <?= escape($item['title']) ?>
                                </a>
                            </div>
                        </td>
                        <td style="text-align: right; padding: 0.75rem;">
                            <?= number_format($item['price'], 2) ?>€
                        </td>
                        <td style="text-align: center; padding: 0.75rem;">
                            <form action="<?= BASE_URL ?>/cart/update" method="POST"
                                  style="display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                                <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                                <input type="hidden" name="article_id" value="<?= (int) $item['id'] ?>">
                                <input type="number" name="quantity"
                                       value="<?= (int) $item['quantity'] ?>"
                                       min="1" max="<?= (int) $item['max_quantity'] ?>"
                                       style="width: 60px; text-align: center; padding: 0.25rem;">
                                <button type="submit" style="padding: 0.25rem 0.5rem; cursor: pointer;">
                                    Mettre à jour
                                </button>
                            </form>
                        </td>
                        <td style="text-align: right; padding: 0.75rem;">
                            <?= number_format($item['price'] * $item['quantity'], 2) ?>€
                        </td>
                        <td style="text-align: center; padding: 0.75rem;">
                            <form action="<?= BASE_URL ?>/cart/remove" method="POST">
                                <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                                <input type="hidden" name="article_id" value="<?= (int) $item['id'] ?>">
                                <button type="submit"
                                        style="background: none; border: none; color: red; cursor: pointer; font-size: 1.2rem;"
                                        title="Retirer du panier">&#x2715;</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-top: 1.5rem; flex-wrap: wrap; gap: 1rem;">
            <form action="<?= BASE_URL ?>/cart/clear" method="POST">
                <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                <button type="submit"
                        onclick="return confirm('Vider tout le panier ?')"
                        style="padding: 0.5rem 1rem; cursor: pointer; color: red; background: none; border: 1px solid red; border-radius: 4px;">
                    Vider le panier
                </button>
            </form>

            <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 0.75rem;">
                <p style="font-size: 1.25rem; font-weight: bold; margin: 0;">
                    Total : <?= number_format($total, 2) ?>€
                </p>
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <a href="<?= BASE_URL ?>/products">Continuer mes achats</a>
                    <form action="<?= BASE_URL ?>/cart/checkout" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                        <button type="submit" onclick="return confirm('Confirmer la commande ?')"
                                style="padding: 0.75rem 1.5rem; background: #333; color: #fff; border-radius: 4px; cursor: pointer; border: none; font-size: 1rem;">
                            Passer la commande
                        </button>
                    </form>
                </div>
            </div>
        </div>

    <?php endif; ?>
</section>
