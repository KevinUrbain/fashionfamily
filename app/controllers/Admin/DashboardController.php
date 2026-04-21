<?php
require_once __DIR__ . '/../../../core/BaseController.php';
require_once __DIR__ . '/../../../utils/Auth.php';
require_once __DIR__ . '/../../../utils/Validator.php';
require_once __DIR__ . '/../../../utils/Sanitizer.php';
require_once __DIR__ . '/../../models/User.php';
require_once __DIR__ . '/../../models/Article.php';
require_once __DIR__ . '/../../models/Order.php';

class DashboardController extends BaseController
{
    private User    $userModel;
    private Article $articleModel;
    private Order   $orderModel;

    /**
     * Initialise le contrôleur avec les modèles User, Article et Order.
     */
    public function __construct()
    {
        $this->userModel    = new User();
        $this->articleModel = new Article();
        $this->orderModel   = new Order();
    }

    /**
     * Affiche la page principale du panel d'administration avec la sidebar.
     *
     * @route GET /admin
     */
    public function index(): void
    {
        Auth::requireAdmin();

        $this->renderAdmin('admin/index', [
            'user' => $this->userModel->getById(Auth::currentUserId()),
        ], 'Admin Dashboard');
    }

    /**
     * Retourne la section dashboard chargée via AJAX.
     * Fournit les KPIs globaux et les articles récents / les plus chers.
     *
     * @route GET /admin/dashboard
     */
    public function dashboardSection(): void
    {
        Auth::requireAdmin();
        $this->renderPartial('admin/sections/dashboard', [
            'totalUsers'     => $this->userModel->count(),
            'totalArticles'  => $this->articleModel->count(),
            'totalOrders'    => $this->orderModel->count(),
            'topArticles'    => $this->articleModel->getTopByPrice(3),
            'recentArticles' => $this->articleModel->getLatest(5),
        ]);
    }

    /**
     * Retourne la section produits chargée via AJAX.
     * Supporte une recherche optionnelle via le paramètre GET `q`.
     *
     * @route GET /admin/products
     */
    public function productsSection(): void
    {
        Auth::requireAdmin();
        $query    = Sanitizer::clean($_GET['q'] ?? '');
        $articles = $query !== '' ? $this->articleModel->search($query) : $this->articleModel->getAll();
        $this->renderPartial('admin/sections/products', [
            'articles' => $articles,
            'search'   => $query,
        ]);
    }

    /**
     * Retourne la section clients chargée via AJAX.
     * Supporte une recherche optionnelle via le paramètre GET `q`.
     *
     * @route GET /admin/customers
     */
    public function customersSection(): void
    {
        Auth::requireAdmin();
        $query = Sanitizer::clean($_GET['q'] ?? '');
        $users = $query !== '' ? $this->userModel->search($query) : $this->userModel->getAll();
        $this->renderPartial('admin/sections/customers', [
            'users'  => $users,
            'search' => $query,
        ]);
    }

    /**
     * Retourne la section commandes chargée via AJAX.
     * Supporte une recherche optionnelle via le paramètre GET `q`.
     *
     * @route GET /admin/orders
     */
    public function ordersSection(): void
    {
        Auth::requireAdmin();
        $query  = Sanitizer::clean($_GET['q'] ?? '');
        $orders = $query !== '' ? $this->orderModel->search($query) : $this->orderModel->getAll();
        $this->renderPartial('admin/sections/orders', [
            'orders' => $orders,
            'search' => $query,
        ]);
    }

    /**
     * Retourne la section avis chargée via AJAX.
     *
     * @route GET /admin/reviews
     */
    public function reviewsSection(): void
    {
        Auth::requireAdmin();
        $this->renderPartial('admin/sections/reviews', []);
    }

    /**
     * Retourne la section paramètres chargée via AJAX.
     * Passe le profil de l'administrateur connecté à la vue.
     *
     * @route GET /admin/settings
     */
    public function settingsSection(): void
    {
        Auth::requireAdmin();
        $this->renderPartial('admin/sections/settings', [
            'user' => $this->userModel->getById(Auth::currentUserId()),
        ]);
    }

    /**
     * Retourne les données de statistiques au format JSON pour les graphiques.
     * Contient les inscriptions et publications par mois (6 derniers mois).
     *
     * @route GET /admin/stats
     */
    public function stats(): void
    {
        Auth::requireAdmin();
        $this->json([
            'articles' => $this->articleModel->countByMonth(),
            'users'    => $this->userModel->countByMonth(),
        ]);
    }

    /**
     * Met à jour le profil (nom et email) de l'administrateur connecté.
     *
     * @route POST /admin/update-profile
     */
    public function updateProfile(): void
    {
        Auth::requireAdmin();

        if (!Session::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            $this->redirect('/admin');
            return;
        }

        $id    = Auth::currentUserId();
        $name  = Sanitizer::clean($_POST['name'] ?? '');
        $email = Sanitizer::clean($_POST['email'] ?? '');

        $validator = new Validator();
        $errors    = $validator->validate(
            ['name' => $name, 'email' => $email],
            ['name' => ['required'], 'email' => ['required', 'email']]
        );

        if ($validator->hasErrors()) {
            Session::setFlash('error', implode(', ', Validator::flattenErrors($errors)));
            $this->redirect('/admin');
            return;
        }

        $this->userModel->update($id, ['name' => $name, 'email' => $email, 'role' => 'admin']);
        Session::setFlash('success', 'Profil mis à jour.');
        $this->redirect('/admin');
    }

    /**
     * Met à jour le mot de passe de l'administrateur connecté.
     * Vérifie l'ancien mot de passe avant d'appliquer le changement.
     *
     * @route POST /admin/update-password
     */
    public function updatePassword(): void
    {
        Auth::requireAdmin();

        if (!Session::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            $this->redirect('/admin');
            return;
        }

        $current = $_POST['current_password'] ?? '';
        $new     = $_POST['new_password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';

        $currentUser     = $this->userModel->getById(Auth::currentUserId());
        $userWithPassword = $this->userModel->getByEmail($currentUser['email'] ?? '');

        if (!password_verify($current, $userWithPassword['password'] ?? '')) {
            Session::setFlash('error', 'Mot de passe actuel incorrect.');
            $this->redirect('/admin');
            return;
        }

        if ($new !== $confirm || strlen($new) < 6) {
            Session::setFlash('error', 'Les mots de passe ne correspondent pas ou sont trop courts (min. 6 caractères).');
            $this->redirect('/admin');
            return;
        }

        $this->userModel->changePassword(Auth::currentUserId(), $new);
        Session::setFlash('success', 'Mot de passe mis à jour.');
        $this->redirect('/admin');
    }
}
