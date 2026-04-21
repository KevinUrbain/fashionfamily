<?php $successMsg = getFlashMessage('success'); ?>
<?php $errorMsg   = getFlashMessage('error');   ?>

<section class="contact-wrapper">
    <div class="contact-container">

        <!-- Left col -->
        <div class="contact-info">
            <h1 class="contact-info__title">Contactez-nous</h1>
            <p class="contact-info__sub">Une question, une suggestion ou un problème&nbsp;? Écrivez-nous, nous vous répondrons rapidement.</p>

            <ul class="contact-info__list">
                <li>
                    <span class="contact-info__icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                            <polyline points="22,6 12,13 2,6"/>
                        </svg>
                    </span>
                    <?= escape(CONTACT_EMAIL) ?>
                </li>
                <li>
                    <span class="contact-info__icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <polyline points="12 6 12 12 16 14"/>
                        </svg>
                    </span>
                    Réponse sous 24&nbsp;h
                </li>
            </ul>
        </div>

        <!-- Right col : form -->
        <div class="contact-form-col">

            <?php if ($successMsg): ?>
                <div class="contact-alert contact-alert--success">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                    <?= escape($successMsg) ?>
                </div>
            <?php endif; ?>

            <?php if ($errorMsg): ?>
                <div class="contact-alert contact-alert--error">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                    </svg>
                    <?= escape($errorMsg) ?>
                </div>
            <?php endif; ?>

            <form class="contact-form" method="POST" action="<?= BASE_URL ?>/home/contact">
                <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">

                <div class="contact-form__row">
                    <div class="contact-form__group">
                        <label class="contact-form__label" for="cf-name">Nom complet</label>
                        <input class="contact-form__input" type="text" id="cf-name" name="name"
                               placeholder="Jean Dupont" required>
                    </div>
                    <div class="contact-form__group">
                        <label class="contact-form__label" for="cf-email">Email</label>
                        <input class="contact-form__input" type="email" id="cf-email" name="email"
                               placeholder="jean@exemple.fr" required>
                    </div>
                </div>

                <div class="contact-form__group">
                    <label class="contact-form__label" for="cf-subject">Sujet</label>
                    <input class="contact-form__input" type="text" id="cf-subject" name="subject"
                           placeholder="Objet de votre message"
                           value="<?= escape($_GET['subject'] ?? '') ?>" required>
                </div>

                <div class="contact-form__group">
                    <label class="contact-form__label" for="cf-message">Message</label>
                    <textarea class="contact-form__textarea" id="cf-message" name="message"
                              rows="6" placeholder="Décrivez votre demande..." required><?= escape($_GET['message'] ?? '') ?></textarea>
                </div>

                <button class="contact-form__submit" type="submit">
                    Envoyer le message
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="22" y1="2" x2="11" y2="13"/>
                        <polygon points="22 2 15 22 11 13 2 9 22 2"/>
                    </svg>
                </button>
            </form>
        </div>

    </div>
</section>
