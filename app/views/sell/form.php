<?php $errorMsg = getFlashMessage('error'); ?>
<?php if ($errorMsg): ?>
    <p style="color: red; text-align: center; padding: 0.5rem;"><?= escape($errorMsg) ?></p>
<?php endif; ?>

<section class="sell-form" style="padding: 2rem; max-width: 700px; margin: 0 auto;">
    <h1>Mettre un article en vente</h1>

    <?php if (!empty($errors)): ?>
        <ul style="background: #fdecea; border: 1px solid #f5c6cb; border-radius: 4px; padding: 1rem 1rem 1rem 2rem; margin-bottom: 1.5rem; color: #721c24;">
            <?php foreach ($errors as $error): ?>
                <li><?= escape($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form action="<?= BASE_URL ?>/sell" method="POST" enctype="multipart/form-data"
          style="display: flex; flex-direction: column; gap: 1.25rem;">
        <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">

        <div>
            <label for="title" style="display: block; margin-bottom: 0.3rem; font-weight: bold;">
                Titre <span style="color: red;">*</span>
            </label>
            <input type="text" id="title" name="title"
                   value="<?= escape($old['title'] ?? '') ?>"
                   maxlength="255" required
                   style="width: 100%; padding: 0.5rem; box-sizing: border-box;">
        </div>

        <div>
            <label for="description" style="display: block; margin-bottom: 0.3rem; font-weight: bold;">
                Description
            </label>
            <textarea id="description" name="description" rows="4"
                      style="width: 100%; padding: 0.5rem; box-sizing: border-box; resize: vertical;"><?= escape($old['description'] ?? '') ?></textarea>
        </div>

        <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 150px;">
                <label for="price" style="display: block; margin-bottom: 0.3rem; font-weight: bold;">
                    Prix (€) <span style="color: red;">*</span>
                </label>
                <input type="number" id="price" name="price"
                       value="<?= escape($old['price'] ?? '') ?>"
                       min="0.01" step="0.01" required
                       style="width: 100%; padding: 0.5rem; box-sizing: border-box;">
            </div>

            <div style="flex: 1; min-width: 120px;">
                <label for="quantity" style="display: block; margin-bottom: 0.3rem; font-weight: bold;">
                    Quantité <span style="color: red;">*</span>
                </label>
                <input type="number" id="quantity" name="quantity"
                       value="<?= escape($old['quantity'] ?? '1') ?>"
                       min="1" required
                       style="width: 100%; padding: 0.5rem; box-sizing: border-box;">
            </div>
        </div>

        <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 180px;">
                <label for="category" style="display: block; margin-bottom: 0.3rem; font-weight: bold;">
                    Catégorie <span style="color: red;">*</span>
                </label>
                <select id="category" name="category" required
                        style="width: 100%; padding: 0.5rem; box-sizing: border-box;">
                    <option value="">-- Choisir --</option>
                    <?php foreach ($categories as $value => $label): ?>
                        <option value="<?= escape($value) ?>"
                            <?= ($old['category'] ?? '') === $value ? 'selected' : '' ?>>
                            <?= escape($label) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div style="flex: 1; min-width: 180px;">
                <label for="article_condition" style="display: block; margin-bottom: 0.3rem; font-weight: bold;">
                    État <span style="color: red;">*</span>
                </label>
                <select id="article_condition" name="article_condition" required
                        style="width: 100%; padding: 0.5rem; box-sizing: border-box;">
                    <option value="">-- Choisir --</option>
                    <?php foreach ($conditions as $value => $label): ?>
                        <option value="<?= escape($value) ?>"
                            <?= ($old['article_condition'] ?? '') === $value ? 'selected' : '' ?>>
                            <?= escape($label) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div>
            <label for="image" style="display: block; margin-bottom: 0.3rem; font-weight: bold;">
                Photo <span style="color: red;">*</span>
            </label>
            <input type="file" id="image" name="image"
                   accept=".jpg,.jpeg,.png,.gif" required
                   style="width: 100%;">
            <small style="color: #666;">Formats acceptés : JPG, PNG, GIF — 5 Mo max</small>
        </div>

        <div style="display: flex; gap: 1rem; align-items: center; margin-top: 0.5rem;">
            <button type="submit" style="padding: 0.75rem 2rem; cursor: pointer; background: #333; color: #fff; border: none; border-radius: 4px; font-size: 1rem;">
                Mettre en vente
            </button>
            <a href="<?= BASE_URL ?>/products" style="color: #666;">Annuler</a>
        </div>
    </form>
</section>
