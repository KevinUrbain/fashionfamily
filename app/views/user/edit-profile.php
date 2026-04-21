<?php
$success = Session::getFlash('success');
$error   = Session::getFlash('error');
?>

<div class="dashboard-wrapper" style="padding: 2rem; max-width: 600px; margin: 0 auto;">
    <h1>Modifier mon profil</h1>
    <a href="<?= BASE_URL ?>/dashboard" style="display:inline-block; margin-bottom: 1.5rem;">&larr; Retour au dashboard</a>

    <?php if ($success): ?>
        <div style="padding: 0.75rem 1rem; margin-bottom: 1rem; border-radius: 4px;
            background: #d4edda; color: #155724; border: 1px solid #c3e6cb;">
            <?= escape($success) ?>
        </div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div style="padding: 0.75rem 1rem; margin-bottom: 1rem; border-radius: 4px;
            background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb;">
            <?= escape($error) ?>
        </div>
    <?php endif; ?>

    <!-- Modifier nom / email -->
    <section style="margin-bottom: 2.5rem;">
        <h2>Informations personnelles</h2>
        <form action="<?= BASE_URL ?>/update-profile" method="POST">
            <input type="hidden" name="csrf_token" value="<?= Session::generateCsrfToken() ?>">

            <div style="margin-bottom: 1rem;">
                <label for="name">Nom</label><br>
                <input type="text" id="name" name="name"
                       value="<?= escape($user['name']) ?>"
                       required style="width: 100%; padding: 0.5rem; margin-top: 0.25rem;">
            </div>

            <div style="margin-bottom: 1rem;">
                <label for="email">Email</label><br>
                <input type="email" id="email" name="email"
                       value="<?= escape($user['email']) ?>"
                       required style="width: 100%; padding: 0.5rem; margin-top: 0.25rem;">
            </div>

            <button type="submit" style="padding: 0.5rem 1.5rem;">Enregistrer</button>
        </form>
    </section>

    <!-- Changer mot de passe -->
    <section>
        <h2>Changer le mot de passe</h2>
        <form action="<?= BASE_URL ?>/update-password" method="POST">
            <input type="hidden" name="csrf_token" value="<?= Session::generateCsrfToken() ?>">

            <div style="margin-bottom: 1rem;">
                <label for="current_password">Mot de passe actuel</label><br>
                <input type="password" id="current_password" name="current_password"
                       required style="width: 100%; padding: 0.5rem; margin-top: 0.25rem;">
            </div>

            <div style="margin-bottom: 1rem;">
                <label for="new_password">Nouveau mot de passe</label><br>
                <input type="password" id="new_password" name="new_password"
                       required minlength="6" style="width: 100%; padding: 0.5rem; margin-top: 0.25rem;">
            </div>

            <div style="margin-bottom: 1rem;">
                <label for="confirm_password">Confirmer le nouveau mot de passe</label><br>
                <input type="password" id="confirm_password" name="confirm_password"
                       required minlength="6" style="width: 100%; padding: 0.5rem; margin-top: 0.25rem;">
            </div>

            <button type="submit" style="padding: 0.5rem 1.5rem;">Changer le mot de passe</button>
        </form>
    </section>
</div>
