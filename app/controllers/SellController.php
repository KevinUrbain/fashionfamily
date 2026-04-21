<?php
require_once __DIR__ . '/../../core/BaseController.php';
require_once __DIR__ . '/../models/Article.php';

class SellController extends BaseController
{
    private Article $articleModel;

    private const CATEGORIES = [
        'vetements' => 'Vêtements',
        'chaussures' => 'Chaussures',
        'accessoires' => 'Accessoires',
        'electronique' => 'Électronique',
        'informatique' => 'Informatique',
        'mobilier' => 'Mobilier',
        'maison' => 'Maison',
        'sport' => 'Sport',
        'jeux-video' => 'Jeux vidéo',
        'autre' => 'Autre',
    ];

    private const CONDITIONS = [
        'new' => 'Neuf',
        'like_new' => 'Comme neuf',
        'good' => 'Bon état',
        'fair' => 'État correct',
        'poor' => 'Usé',
    ];

    /**
     * Initialise le contrôleur avec le modèle Article.
     */
    public function __construct()
    {
        $this->articleModel = new Article();
    }

    /**
     * Affiche le formulaire de mise en vente d'un article.
     * Requiert que l'utilisateur soit connecté.
     *
     * @route GET /sell
     */
    public function form(): void
    {
        Auth::requireLogin();

        $this->render('sell/form', [
            'categories' => self::CATEGORIES,
            'conditions' => self::CONDITIONS,
            'description' => APP_NAME . ' - Mettre un article en vente',
        ], APP_NAME . ' - Vendre un article');
    }

    /**
     * Traite la soumission du formulaire de mise en vente.
     * Valide les champs, gère l'upload de l'image puis crée l'article en base.
     *
     * @route POST /sell
     */
    public function store(): void
    {
        Auth::requireLogin();

        if (!Session::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            Flash::set('error', 'Token CSRF invalide.');
            $this->redirect('/sell');
            return;
        }

        $errors = $this->validate($_POST, $_FILES['image'] ?? null);

        if (!empty($errors)) {
            // Repasser les données saisies pour pré-remplir le formulaire
            $this->render('sell/form', [
                'categories' => self::CATEGORIES,
                'conditions' => self::CONDITIONS,
                'errors' => $errors,
                'old' => $_POST,
                'description' => APP_NAME . ' - Mettre un article en vente',
            ], APP_NAME . ' - Vendre un article');
            return;
        }

        $imagePath = $this->handleUpload($_FILES['image']);

        if ($imagePath === null) {
            $this->render('sell/form', [
                'categories' => self::CATEGORIES,
                'conditions' => self::CONDITIONS,
                'errors' => ["L'image n'a pas pu être enregistrée. Veuillez réessayer."],
                'old' => $_POST,
                'description' => APP_NAME . ' - Mettre un article en vente',
            ], APP_NAME . ' - Vendre un article');
            return;
        }

        $this->articleModel->create([
            'user_id' => Auth::currentUserId(),
            'title' => Sanitizer::clean($_POST['title']),
            'description' => Sanitizer::clean($_POST['description'] ?? ''),
            'image_path' => $imagePath,
            'price' => (float) $_POST['price'],
            'quantity' => (int) $_POST['quantity'],
            'category' => $_POST['category'],
            'article_condition' => $_POST['article_condition'],
        ]);

        Flash::set('success', 'Votre article a bien été mis en vente !');
        $this->redirect('/products');
    }

    /**
     * Valide les données du formulaire de vente.
     * Contrôle le titre, le prix, la quantité, la catégorie, la condition et le fichier image.
     *
     * @param array      $post Données POST du formulaire.
     * @param array|null $file Fichier uploadé ($_FILES['image']) ou null si absent.
     * @return array Liste des messages d'erreur (vide si tout est valide).
     */
    private function validate(array $post, ?array $file): array
    {
        $errors = [];

        if (empty(trim($post['title'] ?? ''))) {
            $errors[] = 'Le titre est obligatoire.';
        } elseif (mb_strlen(trim($post['title'])) > 255) {
            $errors[] = 'Le titre ne doit pas dépasser 255 caractères.';
        }

        $price = $post['price'] ?? '';
        if ($price === '' || !is_numeric($price) || (float) $price <= 0) {
            $errors[] = 'Le prix doit être un nombre positif.';
        }

        $qty = $post['quantity'] ?? '';
        if ($qty === '' || !ctype_digit((string) $qty) || (int) $qty < 1) {
            $errors[] = 'La quantité doit être un entier supérieur ou égal à 1.';
        }

        if (!array_key_exists($post['category'] ?? '', self::CATEGORIES)) {
            $errors[] = 'Veuillez choisir une catégorie valide.';
        }

        if (!array_key_exists($post['article_condition'] ?? '', self::CONDITIONS)) {
            $errors[] = 'Veuillez choisir un état valide.';
        }

        if (empty($file) || $file['error'] === UPLOAD_ERR_NO_FILE) {
            $errors[] = 'Une photo est obligatoire.';
        } elseif ($file['error'] !== UPLOAD_ERR_OK) {
            $errors[] = 'Erreur lors de l\'envoi du fichier.';
        } elseif ($file['size'] > MAX_UPLOAD_SIZE) {
            $errors[] = 'La photo ne doit pas dépasser 5 Mo.';
        } else {
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif'], true)) {
                $errors[] = 'Formats acceptés : JPG, PNG, GIF.';
            }
        }

        return $errors;
    }

    /**
     * Déplace le fichier uploadé vers le dossier public/uploads et retourne son chemin relatif.
     * Génère un nom de fichier unique pour éviter les collisions.
     *
     * @param array $file Entrée du tableau $_FILES pour l'image.
     * @return string|null Chemin relatif public (ex. /uploads/article_xxx.jpg) ou null en cas d'échec.
     */
    private function handleUpload(array $file): ?string
    {
        $uploadDir = PUBLIC_PATH . '/uploads/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $filename = uniqid('article_', true) . '.' . $ext;
        $dest = $uploadDir . $filename;

        if (!move_uploaded_file($file['tmp_name'], $dest)) {
            return null;
        }

        return '/uploads/' . $filename;
    }
}
