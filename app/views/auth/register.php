<section class="login">
    <div class="login_container">
        <h2>Inscription</h2>
        <p>Fashion Family &gt; Inscription</p>
    </div>
</section>

<section class="auth">
    <div class="auth_container">
        <h1>Créer un compte</h1>

        <?php if (!empty($errors)): ?>
            <ul style="color: red; list-style: none; padding: 0;">
                <?php foreach ($errors as $error): ?>
                    <li><?= escape($error) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>/register" method="POST">
            <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">

            <label for="name">Nom</label>
            <input type="text" id="name" name="name" value="<?= escape($old['name'] ?? '') ?>"
                autocomplete="name" maxlength="100" required />

            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?= escape($old['email'] ?? '') ?>"
                autocomplete="email" maxlength="254" required />

            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password"
                autocomplete="new-password" minlength="6" maxlength="72" required />

            <label for="confirm_password">Confirmer le mot de passe</label>
            <input type="password" id="confirm_password" name="confirm_password"
                autocomplete="new-password" minlength="6" maxlength="72" required />

            <button type="submit">S'inscrire</button>
        </form>

        <p>Déjà un compte ? <a href="<?= BASE_URL ?>/login">Se connecter</a></p>
    </div>
</section>
