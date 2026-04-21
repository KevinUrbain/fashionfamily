<section class="msg-wrapper">
    <div class="msg-inbox">

        <div class="msg-inbox__header">
            <h1 class="msg-inbox__title">Messagerie</h1>
            <?php $totalUnread = array_sum(array_column($conversations, 'unread_count')); ?>
            <?php if ($totalUnread > 0): ?>
                <span class="msg-badge"><?= $totalUnread ?></span>
            <?php endif; ?>
        </div>

        <?php $flash = getFlashMessage('error'); ?>
        <?php if ($flash): ?>
            <div class="msg-alert msg-alert--error"><?= escape($flash) ?></div>
        <?php endif; ?>

        <?php if (empty($conversations)): ?>
            <div class="msg-empty">
                <svg xmlns="http://www.w3.org/2000/svg" width="52" height="52" viewBox="0 0 24 24"
                     fill="none" stroke="#d1d5db" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                </svg>
                <p class="msg-empty__title">Aucune conversation</p>
                <p class="msg-empty__sub">Commencez par contacter un vendeur depuis la page d'un article.</p>
                <a class="msg-empty__cta" href="<?= BASE_URL ?>/products">Parcourir les articles</a>
            </div>
        <?php else: ?>
            <ul class="msg-list">
                <?php foreach ($conversations as $conv): ?>
                    <?php
                        $isUnread = (int) $conv['unread_count'] > 0;
                        $isSent   = (int) $conv['sender_id'] === $currentUserId;
                        $preview  = mb_strimwidth($conv['body'], 0, 60, '…');
                        $initials = mb_strtoupper(mb_substr($conv['partner_name'], 0, 1));
                    ?>
                    <li class="msg-list__item <?= $isUnread ? 'msg-list__item--unread' : '' ?>">
                        <a class="msg-list__link" href="<?= BASE_URL ?>/messages/conversation?user=<?= (int) $conv['partner_id'] ?>">
                            <span class="msg-avatar"><?= escape($initials) ?></span>
                            <div class="msg-list__body">
                                <div class="msg-list__top">
                                    <span class="msg-list__name"><?= escape($conv['partner_name']) ?></span>
                                    <time class="msg-list__time">
                                        <?= date('d/m H\hi', strtotime($conv['created_at'])) ?>
                                    </time>
                                </div>
                                <p class="msg-list__preview">
                                    <?= $isSent ? '<span class="msg-you">Vous : </span>' : '' ?><?= escape($preview) ?>
                                </p>
                            </div>
                            <?php if ($isUnread): ?>
                                <span class="msg-badge msg-badge--sm"><?= (int) $conv['unread_count'] ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

    </div>
</section>
