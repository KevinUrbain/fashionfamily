<div class="layout">
    <aside class="sidebar">
        <div class="logo">
            <img src="<?= BASE_URL ?>/img/logo_footer.png" alt="Logo" />
            <h1>Admin</h1>
        </div>
        <div class="sidebar_nav">
            <nav>
                <ul>
                    <li><img src="<?= BASE_URL ?>/img/img-dash/icone_dash.png" alt="" />
                        <a href="<?= BASE_URL ?>/admin">Dashboard</a></li>
                    <li><img src="<?= BASE_URL ?>/img/img-dash/icone_customers.png" alt="" />
                        <a href="<?= BASE_URL ?>/admin">Customers</a></li>
                </ul>
            </nav>
        </div>
    </aside>

    <div class="main">
        <header class="header">
            <h1>Admin &gt; Modifier utilisateur</h1>
            <a href="<?= BASE_URL ?>/logout">
                <img src="<?= BASE_URL ?>/img/img-dash/logout.png" alt="Déconnexion" />
            </a>
        </header>

        <div class="content">
            <div class="edit-user-wrapper">
                <h2>Modifier l'utilisateur</h2>

                <?php if (!empty($errors)): ?>
                    <ul class="form-errors">
                        <?php foreach ($errors as $error): ?>
                            <li><?= escape($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>

                <form method="POST" action="<?= BASE_URL ?>/admin/users/edit">
                    <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                    <input type="hidden" name="id" value="<?= (int) $user['id'] ?>">

                    <label for="name">Nom</label>
                    <input type="text" id="name" name="name" value="<?= escape($user['name']) ?>" required>

                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?= escape($user['email']) ?>" required>

                    <label for="role">Rôle</label>
                    <select id="role" name="role">
                        <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>Utilisateur</option>
                        <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Administrateur</option>
                    </select>

                    <div class="form-actions">
                        <button type="submit">Enregistrer</button>
                        <a href="<?= BASE_URL ?>/admin">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
