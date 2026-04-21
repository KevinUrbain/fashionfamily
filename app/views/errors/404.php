<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page non trouvée</title>
    <link rel="stylesheet" href="<?= defined('BASE_URL') ? BASE_URL : '' ?>/css/styles.css">
</head>
<body>
    <div style="text-align: center; padding: 80px 20px; font-family: Arial, sans-serif;">
        <h1 style="font-size: 96px; margin: 0; color: #e74c3c;">404</h1>
        <h2>Page introuvable</h2>
        <p style="color: #555;">La page que vous recherchez n'existe pas ou a été déplacée.</p>
        <a href="<?= defined('BASE_URL') ? BASE_URL : '/' ?>"
           style="color: #3498db; text-decoration: none; font-size: 1.1rem;">
            &larr; Retour à l'accueil
        </a>

        <?php if (defined('ENVIRONMENT') && ENVIRONMENT === 'development'): ?>
            <div style="margin-top: 2rem; padding: 1rem; background: #f8f9fa; border-radius: 5px; text-align: left; max-width: 600px; margin-left: auto; margin-right: auto;">
                <strong>Debug :</strong>
                <p>URL demandée : <?= htmlspecialchars($_SERVER['REQUEST_URI'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
                <p>Méthode : <?= htmlspecialchars($_SERVER['REQUEST_METHOD'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
