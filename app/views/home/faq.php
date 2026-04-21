<section class="static-page">
    <div class="static-page__container">

        <div class="static-page__hero">
            <h1>Questions fréquentes</h1>
            <p class="static-page__lead">Vous avez une question ? Vous trouverez probablement la réponse ici.</p>
        </div>

        <?php
        $faqs = [
            'Acheter' => [
                ['q' => 'Comment acheter un article ?',
                 'r' => 'Parcourez les articles, ajoutez celui qui vous plaît au panier et validez votre commande. Le vendeur sera notifié et vous recevrez une confirmation.'],
                ['q' => 'Puis-je contacter le vendeur avant d\'acheter ?',
                 'r' => 'Oui. Sur la fiche de chaque article, un bouton "Contacter le vendeur" vous permet d\'échanger directement via notre messagerie interne.'],
                ['q' => 'Les articles sont-ils vérifiés ?',
                 'r' => 'Les vendeurs décrivent honnêtement l\'état de leurs articles (Neuf, Comme neuf, Bon état…). En cas de litige, notre équipe est disponible via le formulaire de contact.'],
            ],
            'Vendre' => [
                ['q' => 'Comment mettre un article en vente ?',
                 'r' => 'Connectez-vous, cliquez sur "Vendre" dans le menu, remplissez le formulaire (titre, description, photo, prix, état) et publiez. C\'est gratuit.'],
                ['q' => 'Quels articles puis-je vendre ?',
                 'r' => 'Vêtements, chaussures, accessoires, bijoux, articles de sport et bien plus. Les articles doivent être conformes à nos conditions générales d\'utilisation.'],
                ['q' => 'Comment modifier ou supprimer mon annonce ?',
                 'r' => 'Rendez-vous sur votre tableau de bord, dans la section "Mes articles en vente". Vous pouvez y gérer toutes vos annonces.'],
            ],
            'Compte & Sécurité' => [
                ['q' => 'Comment créer un compte ?',
                 'r' => 'Cliquez sur l\'icône utilisateur en haut à droite, puis "Créer un compte". L\'inscription est gratuite et rapide.'],
                ['q' => 'Mes données sont-elles protégées ?',
                 'r' => 'Oui. Vos données personnelles ne sont jamais partagées avec des tiers. Consultez notre politique de confidentialité pour plus de détails.'],
            ],
        ];
        ?>

        <div class="faq-sections">
            <?php foreach ($faqs as $section => $items): ?>
                <div class="faq-section">
                    <h2 class="faq-section__title"><?= escape($section) ?></h2>
                    <dl class="faq-list">
                        <?php foreach ($items as $item): ?>
                            <div class="faq-item">
                                <dt class="faq-item__q"><?= escape($item['q']) ?></dt>
                                <dd class="faq-item__a"><?= escape($item['r']) ?></dd>
                            </div>
                        <?php endforeach; ?>
                    </dl>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="static-page__cta-bar">
            <p style="color:#6b7280;font-size:.9rem;">Vous n'avez pas trouvé votre réponse ?</p>
            <a href="<?= BASE_URL ?>/home/contact" class="static-page__cta">Nous contacter</a>
        </div>

    </div>
</section>
