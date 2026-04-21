<?php
require_once __DIR__ . '/../../core/BaseController.php';
require_once __DIR__ . '/../models/Article.php';
require_once __DIR__ . '/../../utils/Sanitizer.php';

class ProductController extends BaseController
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
     * Affiche la liste de tous les articles avec tri optionnel.
     *
     * @route GET /products[?sort=newest|oldest|price_asc|price_desc]
     */
    public function index(): void
    {
        $allowed = ['newest', 'oldest', 'price_asc', 'price_desc'];
        $sort    = in_array($_GET['sort'] ?? '', $allowed) ? $_GET['sort'] : 'newest';

        $this->render('products/index', [
            'articles'    => $this->articleModel->getAll($sort),
            'sort'        => $sort,
            'description' => APP_NAME . ' - Tous les produits',
        ], APP_NAME . ' - Nos articles');
    }

    /**
     * Affiche les articles filtrés par catégorie.
     * Redirige vers le catalogue si le slug de catégorie est absent.
     *
     * @route GET /products/category?cat={slug}
     */
    public function category(): void
    {
        $slug  = Sanitizer::clean($_GET['cat'] ?? '');

        if (empty($slug)) {
            $this->redirect('/products');
            return;
        }

        $label    = Article::categoryLabel($slug);
        $articles = $this->articleModel->getByCategory($slug);

        $this->render('products/category', [
            'articles'    => $articles,
            'slug'        => $slug,
            'label'       => $label,
            'description' => APP_NAME . " - Catégorie : $label",
        ], "$label — " . APP_NAME);
    }

    /**
     * Recherche des articles par mots-clés dans le titre, la description et la catégorie.
     * Nécessite au moins 2 caractères pour déclencher la recherche.
     *
     * @route GET /search?q={query}
     */
    public function search(): void
    {
        $query   = Sanitizer::clean($_GET['q'] ?? $_GET['search'] ?? '');
        $articles = [];

        if (strlen($query) >= 2) {
            $articles = $this->articleModel->search($query);
        }

        $this->render('products/search', [
            'articles'    => $articles,
            'query'       => $query,
            'description' => APP_NAME . ' - Recherche : ' . $query,
        ], 'Recherche : ' . $query);
    }

    /**
     * Affiche la fiche détaillée d'un article avec le nom du vendeur.
     * Redirige vers le catalogue si l'identifiant est invalide ou introuvable.
     *
     * @route GET /products/show?id={id}
     */
    public function show(): void
    {
        $id = (int) ($_GET['id'] ?? 0);

        if (!$id) {
            $this->redirect('/products');
            return;
        }

        $article = $this->articleModel->getById($id);

        if (!$article) {
            $this->redirect('/products');
            return;
        }

        $this->render('products/show', [
            'article' => $article,
            'image' => $article['image_path'],
            'description' => APP_NAME . ' - Découvrez notre article : ' . $article['title'],
        ], $article['title']);
    }


}
