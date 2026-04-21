<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="<?= htmlspecialchars($description ?? 'Fashion Family : Achetez et vendez des articles partout dans le monde', ENT_QUOTES, 'UTF-8') ?>">
    <title><?= htmlspecialchars($title ?? 'Fashion Family', ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/styles.css">
    <?php if (isset($image)): ?>
        <meta property="og:image" content="<?= BASE_URL . htmlspecialchars($image, ENT_QUOTES, 'UTF-8') ?>">
    <?php endif; ?>
</head>

<body>
    <div id="header" data-logged-in="<?= Auth::isLoggedIn() ? '1' : '0' ?>" data-base-url="<?= BASE_URL ?>"
        data-cart-count="<?= Cart::getCount() ?>"></div>

    <?= $content ?>

    <div id="newsletter"></div>
    <div id="footer"></div>
    <script type="module" src="<?= BASE_URL ?>/js/main.js?v=<?= APP_VERSION ?>"></script>
</body>

</html>