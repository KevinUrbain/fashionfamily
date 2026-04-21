<div class="ud-wrapper">

    <!-- En-tête profil -->
    <div class="ud-header">
        <div class="ud-avatar">
            <?= strtoupper(mb_substr(escape($user['name']), 0, 1)) ?>
        </div>
        <div class="ud-header-info">
            <h1 class="ud-username">Bonjour, <?= escape($user['name']) ?> !</h1>
            <span class="ud-role"><?= $user['role'] === 'admin' ? 'Administrateur' : 'Membre' ?></span>
        </div>
        <div class="ud-header-actions">
            <?php if ($user['role'] === 'admin'): ?>
                <a href="<?= BASE_URL ?>/admin" class="ud-btn ud-btn--outline">Back office</a>
            <?php endif; ?>
            <a href="<?= BASE_URL ?>/messages" class="ud-btn ud-btn--outline ud-btn--msg">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                </svg>
                Messagerie
                <?php if ($unreadCount > 0): ?>
                    <span class="ud-msg-badge"><?= $unreadCount ?></span>
                <?php endif; ?>
            </a>
            <a href="<?= BASE_URL ?>/edit-profile" class="ud-btn ud-btn--outline">Modifier mon profil</a>
            <a href="<?= BASE_URL ?>/logout" class="ud-btn ud-btn--ghost">Se déconnecter</a>
        </div>
    </div>

    <!-- Cartes de statistiques -->
    <div class="ud-stats">
        <div class="ud-stat-card">
            <span class="ud-stat-icon">📦</span>
            <span class="ud-stat-value"><?= (int) $user['total_orders'] ?></span>
            <span class="ud-stat-label">Commande<?= $user['total_orders'] > 1 ? 's' : '' ?></span>
        </div>
        <div class="ud-stat-card">
            <span class="ud-stat-icon">🛍️</span>
            <span class="ud-stat-value"><?= number_format((float) $user['total_spent'], 2, ',', ' ') ?> €</span>
            <span class="ud-stat-label">Total dépensé</span>
        </div>
        <div class="ud-stat-card">
            <span class="ud-stat-icon">🏷️</span>
            <span class="ud-stat-value"><?= (int) $user['total_articles'] ?></span>
            <span class="ud-stat-label">Article<?= $user['total_articles'] > 1 ? 's' : '' ?> en vente</span>
        </div>
    </div>

    <div class="ud-content">

        <!-- Colonne gauche : infos profil -->
        <aside class="ud-sidebar">
            <div class="ud-card">
                <h2 class="ud-card-title">Mes informations</h2>
                <ul class="ud-info-list">
                    <li>
                        <span class="ud-info-label">Nom</span>
                        <span class="ud-info-value"><?= escape($user['name']) ?></span>
                    </li>
                    <li>
                        <span class="ud-info-label">Email</span>
                        <span class="ud-info-value"><?= escape($user['email']) ?></span>
                    </li>
                    <li>
                        <span class="ud-info-label">Rôle</span>
                        <span class="ud-info-value"><?= $user['role'] === 'admin' ? 'Administrateur' : 'Membre' ?></span>
                    </li>
                    <li>
                        <span class="ud-info-label">Membre depuis</span>
                        <span class="ud-info-value">
                            <?= date('d/m/Y', strtotime($user['created_at'])) ?>
                        </span>
                    </li>
                </ul>
            </div>

            <!-- Articles en vente -->
            <div class="ud-card">
                <h2 class="ud-card-title">Mes articles en vente</h2>
                <?php if (empty($articles)): ?>
                    <p class="ud-empty">Vous n'avez pas encore d'articles en vente.</p>
                    <a href="<?= BASE_URL ?>/sell" class="ud-btn ud-btn--primary ud-btn--full">Vendre un article</a>
                <?php else: ?>
                    <ul class="ud-article-list">
                        <?php foreach ($articles as $article): ?>
                            <li class="ud-article-item">
                                <?php if (!empty($article['image_path'])): ?>
                                    <img src="<?= BASE_URL . '/' . escape($article['image_path']) ?>"
                                         alt="<?= escape($article['title']) ?>"
                                         class="ud-article-img">
                                <?php else: ?>
                                    <div class="ud-article-img ud-article-img--placeholder">?</div>
                                <?php endif; ?>
                                <div class="ud-article-info">
                                    <span class="ud-article-title"><?= escape($article['title']) ?></span>
                                    <span class="ud-article-price"><?= number_format((float) $article['price'], 2, ',', ' ') ?> €</span>
                                    <span class="ud-article-status ud-status--<?= escape($article['status']) ?>">
                                        <?= escape($article['status']) ?>
                                    </span>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <a href="<?= BASE_URL ?>/sell" class="ud-btn ud-btn--outline ud-btn--full" style="margin-top:1rem;">
                        + Vendre un article
                    </a>
                <?php endif; ?>
            </div>
        </aside>

        <!-- Colonne principale : historique des commandes -->
        <main class="ud-main">
            <div class="ud-card">
                <h2 class="ud-card-title">Historique des commandes</h2>

                <?php if (empty($orders)): ?>
                    <p class="ud-empty">Vous n'avez pas encore passé de commande.</p>
                    <a href="<?= BASE_URL ?>/products" class="ud-btn ud-btn--primary">Découvrir les articles</a>
                <?php else: ?>
                    <div class="ud-orders">
                        <?php foreach ($orders as $order): ?>
                            <div class="ud-order">
                                <div class="ud-order-header">
                                    <div>
                                        <span class="ud-order-id">Commande #<?= $order['id'] ?></span>
                                        <span class="ud-order-date">
                                            <?= date('d/m/Y à H:i', strtotime($order['created_at'])) ?>
                                        </span>
                                    </div>
                                    <div class="ud-order-meta">
                                        <span class="ud-badge ud-badge--<?= escape($order['status']) ?>">
                                            <?php
                                            $labels = [
                                                'pending'   => 'En attente',
                                                'paid'      => 'Payée',
                                                'shipped'   => 'Expédiée',
                                                'delivered' => 'Livrée',
                                                'cancelled' => 'Annulée',
                                            ];
                                            echo $labels[$order['status']] ?? escape($order['status']);
                                            ?>
                                        </span>
                                        <strong class="ud-order-total">
                                            <?= number_format((float) $order['total_price'], 2, ',', ' ') ?> €
                                        </strong>
                                    </div>
                                </div>

                                <?php if (!empty($order['items'])): ?>
                                    <ul class="ud-order-items">
                                        <?php foreach ($order['items'] as $item): ?>
                                            <li class="ud-order-item">
                                                <?php if (!empty($item['image_path'])): ?>
                                                    <img src="<?= BASE_URL . '/' . escape($item['image_path']) ?>"
                                                         alt="<?= escape($item['title']) ?>"
                                                         class="ud-item-img">
                                                <?php else: ?>
                                                    <div class="ud-item-img ud-item-img--placeholder">?</div>
                                                <?php endif; ?>
                                                <div class="ud-item-details">
                                                    <a href="<?= BASE_URL ?>/products/show?id=<?= (int) $item['article_id'] ?>"
                                                       class="ud-item-title">
                                                        <?= escape($item['title']) ?>
                                                    </a>
                                                    <span class="ud-item-qty">Qté : <?= (int) $item['quantity'] ?></span>
                                                </div>
                                                <span class="ud-item-price">
                                                    <?= number_format((float) $item['price'], 2, ',', ' ') ?> €
                                                </span>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </main>

    </div><!-- /.ud-content -->
</div><!-- /.ud-wrapper -->
