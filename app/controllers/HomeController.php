<?php
require_once __DIR__ . '/../../core/BaseController.php';
require_once __DIR__ . '/../models/Article.php';

class HomeController extends BaseController
{
    private Article $articleModel;

    /**
     * Initialise le contrôleur avec le modèle Article.
     */
    public function __construct()
    {
        $this->articleModel = new Article();
    }

    /**
     * Affiche la page d'accueil avec les derniers articles et une sélection aléatoire.
     *
     * @route GET /
     */
    public function index(): void
    {
        $this->render('home/index', [
            'description' => APP_NAME . " : Achetez et vendez des articles partout dans le monde",
            'articlesByDateDesc' => $this->articleModel->getLatest(5),
            'articlesByRandom' => $this->articleModel->getRandom(5),
        ], APP_NAME . ' : Achetez et vendez des articles partout dans le monde');
    }

    /**
     * Affiche la page « À propos ».
     *
     * @route GET /home/about
     */
    public function about(): void
    {
        $this->render('home/about', [
            'description' => APP_NAME . ' - À propos de nous',
        ], 'À propos — ' . APP_NAME);
    }

    /**
     * Affiche la page des questions fréquentes (FAQ).
     *
     * @route GET /home/faq
     */
    public function faq(): void
    {
        $this->render('home/faq', [
            'description' => APP_NAME . ' - Questions fréquentes',
        ], 'FAQ — ' . APP_NAME);
    }

    /**
     * Affiche les conditions générales d'utilisation.
     *
     * @route GET /home/terms
     */
    public function terms(): void
    {
        $this->render('home/terms', [
            'description' => APP_NAME . ' - Conditions générales d\'utilisation',
        ], 'CGU — ' . APP_NAME);
    }

    /**
     * Affiche la politique de confidentialité.
     *
     * @route GET /home/privacy
     */
    public function privacy(): void
    {
        $this->render('home/privacy', [
            'description' => APP_NAME . ' - Politique de confidentialité',
        ], 'Confidentialité — ' . APP_NAME);
    }

    /**
     * Affiche la page des offres d'emploi.
     *
     * @route GET /home/careers
     */
    public function careers(): void
    {
        $this->render('home/careers', [
            'description' => APP_NAME . ' - Rejoignez-nous',
        ], 'Carrières — ' . APP_NAME);
    }
}
