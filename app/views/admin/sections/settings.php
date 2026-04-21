<div class="settings-wrapper">

    <?php $success = getFlashMessage('success'); $error = getFlashMessage('error'); ?>
    <?php if ($success): ?>
        <p class="flash-success"><?= escape($success) ?></p>
    <?php elseif ($error): ?>
        <p class="flash-error"><?= escape($error) ?></p>
    <?php endif; ?>

    <section class="settings-section">
        <h2>Profil</h2>
        <form method="POST" action="<?= BASE_URL ?>/admin/update-profile">
            <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">

            <label for="name">Nom</label>
            <input type="text" id="name" name="name" value="<?= escape($user['name']) ?>" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?= escape($user['email']) ?>" required>

            <button type="submit">Enregistrer</button>
        </form>
    </section>

    <section class="settings-section">
        <h2>Mot de passe</h2>
        <form method="POST" action="<?= BASE_URL ?>/admin/update-password">
            <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">

            <label for="current_password">Mot de passe actuel</label>
            <input type="password" id="current_password" name="current_password" required>

            <label for="new_password">Nouveau mot de passe</label>
            <input type="password" id="new_password" name="new_password" minlength="6" required>

            <label for="confirm_password">Confirmer le mot de passe</label>
            <input type="password" id="confirm_password" name="confirm_password" minlength="6" required>

            <button type="submit">Changer le mot de passe</button>
        </form>
    </section>

</div>
