<section class="msg-wrapper">
    <div class="msg-thread">

        <!-- Header -->
        <div class="msg-thread__header">
            <a class="msg-thread__back" href="<?= BASE_URL ?>/messages">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="15 18 9 12 15 6"/>
                </svg>
                Retour
            </a>
            <div class="msg-thread__partner">
                <span class="msg-avatar msg-avatar--md">
                    <?= escape(mb_strtoupper(mb_substr($partner['name'], 0, 1))) ?>
                </span>
                <span class="msg-thread__partner-name"><?= escape($partner['name']) ?></span>
            </div>
            <?php
                $reportSubject = 'Signalement – ' . $partner['name'];
                $reportMessage = 'Je souhaite signaler l\'utilisateur ' . $partner['name']
                               . ' (ID : ' . (int) $partner['id'] . ').'
                               . "\n\nMotif du signalement :\n";
            ?>
            <a class="msg-report-btn"
               href="<?= BASE_URL ?>/home/contact?subject=<?= urlencode($reportSubject) ?>&message=<?= urlencode($reportMessage) ?>"
               title="Signaler cet utilisateur">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/>
                    <line x1="4" y1="22" x2="4" y2="15"/>
                </svg>
                Signaler
            </a>
        </div>

        <!-- Contexte article (si on vient d'une fiche produit) -->
        <?php if ($articleId && !empty($messages) && !empty($messages[0]['article_title'])): ?>
            <div class="msg-article-ctx">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/>
                </svg>
                À propos de :
                <a href="<?= BASE_URL ?>/products/show?id=<?= (int) $messages[0]['article_ref_id'] ?>">
                    <?= escape($messages[0]['article_title']) ?>
                </a>
            </div>
        <?php elseif ($articleId): ?>
            <div class="msg-article-ctx">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/>
                </svg>
                Concernant un article — <a href="<?= BASE_URL ?>/products/show?id=<?= (int) $articleId ?>">Voir l'article</a>
            </div>
        <?php endif; ?>

        <!-- Messages -->
        <div class="msg-bubbles" id="msg-bubbles">
            <?php if (empty($messages)): ?>
                <p class="msg-bubbles__empty">Envoyez votre premier message à <?= escape($partner['name']) ?>.</p>
            <?php else: ?>
                <?php
                    $prevDate = null;
                    foreach ($messages as $msg):
                        $msgDate = date('d/m/Y', strtotime($msg['created_at']));
                        $isMine  = (int) $msg['sender_id'] === $currentUserId;
                ?>
                    <?php if ($msgDate !== $prevDate): ?>
                        <div class="msg-date-sep"><span><?= $msgDate ?></span></div>
                        <?php $prevDate = $msgDate; ?>
                    <?php endif; ?>

                    <div class="msg-bubble-row <?= $isMine ? 'msg-bubble-row--mine' : 'msg-bubble-row--theirs' ?>">
                        <?php if (!$isMine): ?>
                            <span class="msg-avatar msg-avatar--xs">
                                <?= escape(mb_strtoupper(mb_substr($msg['sender_name'], 0, 1))) ?>
                            </span>
                        <?php endif; ?>
                        <div class="msg-bubble">
                            <?php if (!empty($msg['article_title']) && empty($prevArticleShown)): ?>
                                <span class="msg-bubble__article">
                                    📦 <?= escape($msg['article_title']) ?>
                                </span>
                                <?php $prevArticleShown = true; ?>
                            <?php endif; ?>
                            <p><?= nl2br(escape($msg['body'])) ?></p>
                            <time class="msg-bubble__time">
                                <?= date('H\hi', strtotime($msg['created_at'])) ?>
                                <?php if ($isMine): ?>
                                    <?= $msg['read_at'] ? ' · Lu' : '' ?>
                                <?php endif; ?>
                            </time>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Formulaire d'envoi -->
        <form class="msg-send-form" method="POST" action="<?= BASE_URL ?>/messages/send">
            <input type="hidden" name="csrf_token"   value="<?= generateCsrfToken() ?>">
            <input type="hidden" name="receiver_id"  value="<?= (int) $partner['id'] ?>">
            <?php if ($articleId): ?>
                <input type="hidden" name="article_id" value="<?= (int) $articleId ?>">
            <?php endif; ?>
            <textarea class="msg-send-form__input" name="body" rows="2"
                      placeholder="Votre message…" required></textarea>
            <button class="msg-send-form__btn" type="submit">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="22" y1="2" x2="11" y2="13"/>
                    <polygon points="22 2 15 22 11 13 2 9 22 2"/>
                </svg>
            </button>
        </form>

    </div>
</section>

<script>
    // Scroll automatique vers le bas à l'ouverture
    const bubbles = document.getElementById('msg-bubbles');
    if (bubbles) bubbles.scrollTop = bubbles.scrollHeight;
</script>
