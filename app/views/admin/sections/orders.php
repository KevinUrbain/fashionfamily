<?php
$flashSuccess = Session::getFlash('success');
$flashError   = Session::getFlash('error');
?>

<?php if ($flashSuccess): ?>
    <p class="flash-success" style="margin-bottom: 16px;"><?= escape($flashSuccess) ?></p>
<?php elseif ($flashError): ?>
    <p class="flash-error" style="margin-bottom: 16px;"><?= escape($flashError) ?></p>
<?php endif; ?>

<div class="section-header">
    <h2>Commandes : <?= count($orders) ?></h2>
    <div class="search-bar">
        <input type="text" id="search-input" value="<?= escape($search ?? '') ?>" placeholder="Rechercher par acheteur ou statut…" autocomplete="off" />
        <button type="button" id="search-btn">Rechercher</button>
        <?php if (!empty($search)): ?>
            <button type="button" id="search-reset-btn">Réinitialiser</button>
        <?php endif; ?>
    </div>
</div>

<?php if (empty($orders)): ?>
    <p class="no-results">Aucune commande trouvée<?= !empty($search) ? ' pour « ' . escape($search) . ' »' : '' ?>.</p>
<?php else: ?>
<table class="admin-table">
    <thead>
        <tr>
            <th>#</th>
            <th>Acheteur</th>
            <th>Email</th>
            <th>Total</th>
            <th>Statut</th>
            <th>Date</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($orders as $order): ?>
            <tr>
                <td><?= (int) $order['id'] ?></td>
                <td><?= escape($order['buyer_name']) ?></td>
                <td><?= escape($order['buyer_email']) ?></td>
                <td><?= number_format((float) $order['total_price'], 2) ?>€</td>
                <td>
                    <span class="order-badge order-badge--<?= escape($order['status']) ?>">
                        <?= escape($order['status']) ?>
                    </span>
                </td>
                <td><?= escape($order['created_at']) ?></td>
                <td>
                    <form method="POST" action="<?= BASE_URL ?>/admin/orders/status" class="status-form">
                        <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                        <input type="hidden" name="id" value="<?= (int) $order['id'] ?>">
                        <select name="status" class="status-select" onchange="this.form.submit()">
                            <?php foreach (['pending', 'paid', 'shipped', 'delivered', 'cancelled'] as $s): ?>
                                <option value="<?= $s ?>" <?= $order['status'] === $s ? 'selected' : '' ?>>
                                    <?= ucfirst($s) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>
