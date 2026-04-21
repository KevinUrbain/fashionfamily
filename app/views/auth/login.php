<?php $msg = getFlashMessage('success'); ?>
<?php if ($msg): ?>
    <p style="color: green; text-align:center;"><?= escape($msg) ?></p>
<?php endif; ?>
<?php $errMsg = getFlashMessage('error'); ?>
<?php if ($errMsg): ?>
    <p style="color: red; text-align:center;"><?= escape($errMsg) ?></p>
<?php endif; ?>

<section class="login">
    <div class="login_container">
        <h2>Login</h2>
        <p>Fashion Family &gt; Connexion</p>
    </div>
</section>

<section class="auth">
    <div class="auth_container">
        <h1>Connexion</h1>

        <?php if (!empty($errors)): ?>
            <ul style="color: red; list-style: none; padding: 0;">
                <?php foreach ($errors as $error): ?>
                    <li><?= escape($error) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>/login" method="POST">
            <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">

            <label for="email">Email</label>
            <input type="email" id="email" name="email" autocomplete="email" maxlength="254" required />

            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" autocomplete="current-password" minlength="6" maxlength="72" required />

            <span>Mot de passe oublié ? <a href="#">Réinitialiser</a></span>
            <button type="submit">Se connecter</button>
        </form>

        <p>Pas de compte ? <a href="<?= BASE_URL ?>/register">S'inscrire</a></p>
    </div>
</section>
