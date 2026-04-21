<section>
    <div class="container_top">
        <div class="container_left card">
            <div class="card_header">
                <div class="txt">
                    <h2>Total Articles</h2>
                    <h3>En ligne</h3>
                </div>
                <span id="sales"><?= (int) $totalArticles ?></span>
            </div>
            <div class="img">
                <canvas id="chart-articles"></canvas>
            </div>
        </div>
        <div class="container_middle card">
            <div class="card_header">
                <div class="txt">
                    <h2>Utilisateurs</h2>
                    <h3>Inscrits</h3>
                </div>
                <span id="customers"><?= (int) $totalUsers ?></span>
            </div>
            <div class="img">
                <canvas id="chart-users"></canvas>
            </div>
        </div>
        <?php
        $ordersGoal = 1000;
        $ordersPct = min(100, (int) round($totalOrders / $ordersGoal * 100));
        $ordersLeft = max(0, $ordersGoal - $totalOrders);
        ?>
        <div class="container_right card">
            <div class="card_header">
                <div class="txt">
                    <h2>Commandes</h2>
                    <h3>Objectif mensuel : <?= $ordersGoal ?></h3>
                </div>
                <span id="orders"><?= (int) $totalOrders ?></span>
            </div>
            <div class="orders-progress">
                <p class="orders-left"><span id="orders-left"><?= $ordersLeft ?></span>
                    restante<?= $ordersLeft > 1 ? 's' : '' ?></p>
                <div class="progress-track">
                    <div class="progress-bar" id="orders-bar" style="width: <?= $ordersPct ?>%"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="container_bottom">
        <div class="container_bottom_left card">
            <div class="card_header">
                <div class="txt">
                    <h2>Articles les plus chers</h2>
                    <h3>Top 3</h3>
                </div>
            </div>
            <hr />
            <?php if (!empty($topArticles)): ?>
                <?php
                $maxPrice = max(array_column($topArticles, 'price'));
                ?>
                <div class="total_sales">
                    <span><?= number_format((float) $maxPrice, 2, ',', ' ') ?> €</span>
                    <span class="total"> - Prix le plus haut</span>
                </div>
                <div class="best_seller_list">
                    <?php foreach ($topArticles as $article): ?>
                        <div class="best_seller_item">
                            <span><?= htmlspecialchars($article['title'], ENT_QUOTES, 'UTF-8') ?></span>
                            <span><?= number_format((float) $article['price'], 2, ',', ' ') ?> €</span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="total_sales">
                    <span>0,00 €</span>
                    <span class="total"> - Aucun article</span>
                </div>
            <?php endif; ?>
            <div class="img" style="margin-top: auto;">
                <canvas id="chart-top-articles" style="max-width: 140px; max-height: 140px;"></canvas>
            </div>
        </div>
        <div class="container_bottom_right card">
            <div class="card_header">
                <div class="txt">
                    <h2>Articles récents</h2>
                    <a href="<?= BASE_URL ?>/admin/products" data-page="products"
                        style="position:absolute;top:20px;left:170px;font-size:12px;color:#5c5f6a;line-height:1.75;letter-spacing:5%;border:1px solid #5c5f6a23;border-radius:8px;padding:2px 16px;background:#f6f6f6;text-decoration:none;">Voir
                        tout</a>
                </div>
                <div class="table">
                    <table>
                        <thead>
                            <tr>
                                <th>Article</th>
                                <th>Catégorie</th>
                                <th>Prix</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($recentArticles)): ?>
                                <?php foreach ($recentArticles as $article): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($article['title'], ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><?= htmlspecialchars($article['category'] ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><?= number_format((float) $article['price'], 2, ',', ' ') ?> €</td>
                                        <td><?= htmlspecialchars(ucfirst($article['status'] ?? '-'), ENT_QUOTES, 'UTF-8') ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" style="text-align:center;color:#5c5f6a;">Aucun article pour le moment
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>