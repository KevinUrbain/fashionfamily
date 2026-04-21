<?php
require_once __DIR__ . '/../../core/BaseController.php';
require_once __DIR__ . '/../../utils/Auth.php';
require_once __DIR__ . '/../../utils/Validator.php';
require_once __DIR__ . '/../../utils/Sanitizer.php';
require_once __DIR__ . '/../models/User.php';

class AuthController extends BaseController
{
    private User $userModel;
    private Validator $validator;

    /**
     * Initialise le contrôleur avec le modèle User et le validateur.
     */
    public function __construct()
    {
        $this->userModel = new User();
        $this->validator = new Validator();
    }

    /**
     * Affiche le formulaire de connexion.
     * Redirige vers le dashboard si l'utilisateur est déjà connecté.
     *
     * @route GET /login
     */
    public function loginForm(): void
    {
        if (Auth::isLoggedIn()) {
            $this->redirect('/dashboard');
            return;
        }
        $this->render('auth/login', [], 'Connexion');
    }

    /**
     * Traite la soumission du formulaire de connexion.
     * Authentifie l'utilisateur et démarre sa session en cas de succès.
     *
     * @route POST /login
     */
    public function login(): void
    {
        if (Auth::isLoggedIn()) {
            $this->redirect('/dashboard');
            return;
        }

        if (!Session::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            Session::setFlash('error', 'Session expirée, veuillez réessayer.');
            $this->redirect('/login');
            return;
        }

        $email = Sanitizer::clean($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        $errors = $this->validator->validate(
            ['email' => $email, 'password' => $password],
            ['email' => ['required', 'email'], 'password' => ['required', 'min:6']]
        );

        if ($this->validator->hasErrors()) {
            $this->render('auth/login', [
                'errors' => Validator::flattenErrors($errors),
            ], 'Connexion');
            return;
        }

        $user = $this->userModel->authenticate($email, $password);
        if (!$user) {
            $this->render('auth/login', [
                'errors' => ['Email ou mot de passe incorrect.'],
            ], 'Connexion');
            return;
        }

        Auth::login($user);
        $this->redirect('/dashboard');
    }

    /**
     * Affiche le formulaire d'inscription.
     * Redirige vers le dashboard si l'utilisateur est déjà connecté.
     *
     * @route GET /register
     */
    public function registerForm(): void
    {
        if (Auth::isLoggedIn()) {
            $this->redirect('/dashboard');
            return;
        }
        $this->render('auth/register', [], 'Inscription');
    }

    /**
     * Traite la soumission du formulaire d'inscription.
     * Valide les données, vérifie l'unicité de l'email et crée le compte.
     *
     * @route POST /register
     */
    public function register(): void
    {
        if (Auth::isLoggedIn()) {
            $this->redirect('/dashboard');
            return;
        }

        if (!Session::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            Session::setFlash('error', 'Session expirée, veuillez réessayer.');
            $this->redirect('/register');
            return;
        }

        $name = Sanitizer::clean($_POST['name'] ?? '');
        $email = Sanitizer::clean($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        $errors = $this->validator->validate(
            ['name' => $name, 'email' => $email, 'password' => $password],
            [
                'name' => ['required', 'min:2', 'max:100'],
                'email' => ['required', 'email'],
                'password' => ['required', 'min:6', 'max:72'],
            ]
        );

        if ($password !== $confirmPassword) {
            $errors['confirm_password'][] = 'Les mots de passe ne correspondent pas.';
        }

        if (!empty($errors)) {
            $this->render('auth/register', [
                'errors' => Validator::flattenErrors($errors),
                'old' => ['name' => $name, 'email' => $email],
            ], 'Inscription');
            return;
        }

        if ($this->userModel->emailExists($email)) {
            $this->render('auth/register', [
                'errors' => ['Cette adresse email est déjà utilisée.'],
                'old' => ['name' => $name, 'email' => $email],
            ], 'Inscription');
            return;
        }

        $userId = $this->userModel->create(['name' => $name, 'email' => $email, 'password' => $password]);

        if (!$userId) {
            $this->render('auth/register', [
                'errors' => ['Une erreur est survenue, veuillez réessayer.'],
            ], 'Inscription');
            return;
        }

        Session::setFlash('success', 'Compte créé avec succès ! Vous pouvez maintenant vous connecter.');
        $this->redirect('/login');
    }

    /**
     * Déconnecte l'utilisateur et détruit sa session.
     *
     * @route GET /logout
     */
    public function logout(): void
    {
        Auth::logout();
        $this->redirect('/login');
    }
}
