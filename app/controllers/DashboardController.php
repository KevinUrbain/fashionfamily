<?php
require_once __DIR__ . '/../../core/BaseController.php';
require_once __DIR__ . '/../../utils/Auth.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../models/Article.php';
require_once __DIR__ . '/../models/Message.php';

class DashboardController extends BaseController
{
    private User    $userModel;
    private Order   $orderModel;
    private Article $articleModel;
    private Message $messageModel;

    /**
     * Initialise le contrôleur avec les modèles User, Order, Article et Message.
     */
    public function __construct()
    {
        $this->userModel    = new User();
        $this->orderModel   = new Order();
        $this->articleModel = new Article();
        $this->messageModel = new Message();
    }

    /**
     * Affiche le tableau de bord de l'utilisateur connecté.
     * Agrège ses commandes, ses annonces et le nombre de messages non lus.
     *
     * @route GET /dashboard
     */
    public function index(): void
    {
        Auth::requireLogin('/login');

        $userId  = Auth::currentUserId();
        $user    = $this->userModel->getStatsById($userId);
        $orders  = $this->orderModel->getByBuyerIdWithItems($userId);
        $articles = $this->articleModel->getByUserId($userId);

        $this->render('user/dashboard', [
            'user'         => $user,
            'orders'       => $orders,
            'articles'     => $articles,
            'unreadCount'  => $this->messageModel->countUnread($userId),
        ], 'Mon Dashboard');
    }

    /**
     * Affiche le formulaire de modification du profil de l'utilisateur connecté.
     *
     * @route GET /edit-profile
     */
    public function edit(): void
    {
        Auth::requireLogin('/login');

        $user = $this->userModel->getById(Auth::currentUserId());

        $this->render('user/edit-profile', ['user' => $user], 'Modifier mon profil');
    }

    /**
     * Traite la mise à jour du profil (nom et email) de l'utilisateur connecté.
     * Vérifie l'unicité de l'email avant d'appliquer les modifications.
     *
     * @route POST /update-profile
     */
    public function updateProfile(): void
    {
        Auth::requireLogin('/login');

        if (!Session::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            $this->redirect('/edit-profile');
            return;
        }

        $id = Auth::currentUserId();
        $name = Sanitizer::clean($_POST['name'] ?? '');
        $email = Sanitizer::clean($_POST['email'] ?? '');

        $validator = new Validator();
        $errors = $validator->validate(
            ['name' => $name, 'email' => $email],
            ['name' => ['required'], 'email' => ['required', 'email']]
        );

        if ($validator->hasErrors()) {
            Session::setFlash('error', implode(', ', Validator::flattenErrors($errors)));
            $this->redirect('/edit-profile');
            return;
        }

        if ($this->userModel->emailExists($email, $id)) {
            Session::setFlash('error', 'Cette adresse email est déjà utilisée.');
            $this->redirect('/edit-profile');
            return;
        }

        $currentUser = $this->userModel->getById($id);
        $this->userModel->update($id, ['name' => $name, 'email' => $email, 'role' => $currentUser['role']]);

        Session::set('user', array_merge(Session::get('user') ?? [], ['name' => $name, 'email' => $email]));
        Session::setFlash('success', 'Profil mis à jour.');
        $this->redirect('/edit-profile');
    }

    /**
     * Traite le changement de mot de passe de l'utilisateur connecté.
     * Vérifie l'ancien mot de passe et contrôle la confirmation avant d'appliquer.
     *
     * @route POST /update-password
     */
    public function updatePassword(): void
    {
        Auth::requireLogin('/login');

        if (!Session::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            $this->redirect('/edit-profile');
            return;
        }

        $current = $_POST['current_password'] ?? '';
        $new = $_POST['new_password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';

        $currentUser = $this->userModel->getById(Auth::currentUserId());
        $userWithPassword = $this->userModel->getByEmail($currentUser['email'] ?? '');

        if (!password_verify($current, $userWithPassword['password'] ?? '')) {
            Session::setFlash('error', 'Mot de passe actuel incorrect.');
            $this->redirect('/edit-profile');
            return;
        }

        if ($new !== $confirm || strlen($new) < 6) {
            Session::setFlash('error', 'Les mots de passe ne correspondent pas ou sont trop courts (min. 6 caractères).');
            $this->redirect('/edit-profile');
            return;
        }

        $this->userModel->changePassword(Auth::currentUserId(), $new);
        Session::setFlash('success', 'Mot de passe mis à jour.');
        $this->redirect('/edit-profile');
    }
}
