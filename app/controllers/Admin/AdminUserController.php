<?php
require_once __DIR__ . '/../../../core/BaseController.php';
require_once __DIR__ . '/../../../utils/Auth.php';
require_once __DIR__ . '/../../../utils/Validator.php';
require_once __DIR__ . '/../../../utils/Sanitizer.php';
require_once __DIR__ . '/../../models/User.php';

class AdminUserController extends BaseController
{
    private User $userModel;

    /**
     * Initialise le contrôleur avec le modèle User.
     */
    public function __construct()
    {
        $this->userModel = new User();
    }

    /**
     * Affiche le formulaire d'édition d'un utilisateur.
     *
     * @route GET /admin/users/edit?id={id}
     */
    public function edit(): void
    {
        Auth::requireAdmin();

        $id   = (int) ($_GET['id'] ?? 0);
        $user = $this->userModel->getById($id);

        if (!$user) {
            $this->redirect('/admin');
            return;
        }

        $this->renderAdmin('admin/users/edit', [
            'user' => $user,
        ], 'Modifier utilisateur');
    }

    /**
     * Traite la mise à jour des informations d'un utilisateur.
     *
     * @route POST /admin/users/edit
     */
    public function update(): void
    {
        Auth::requireAdmin();

        $id = (int) ($_POST['id'] ?? 0);

        if (!Session::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            Session::setFlash('error', 'Session expirée, veuillez réessayer.');
            $this->redirect('/admin/users/edit?id=' . $id);
            return;
        }

        $user = $this->userModel->getById($id);
        if (!$user) {
            $this->redirect('/admin');
            return;
        }

        $name  = Sanitizer::clean($_POST['name'] ?? '');
        $email = Sanitizer::clean($_POST['email'] ?? '');
        $role  = in_array($_POST['role'] ?? '', ['user', 'admin']) ? $_POST['role'] : 'user';

        $validator = new Validator();
        $errors    = $validator->validate(
            ['name' => $name, 'email' => $email],
            ['name' => ['required'], 'email' => ['required', 'email']]
        );

        if ($validator->hasErrors()) {
            $this->renderAdmin('admin/users/edit', [
                'user'   => array_merge($user, ['name' => $name, 'email' => $email, 'role' => $role]),
                'errors' => Validator::flattenErrors($errors),
            ], 'Modifier utilisateur');
            return;
        }

        $this->userModel->update($id, ['name' => $name, 'email' => $email, 'role' => $role]);
        Session::setFlash('success', 'Utilisateur mis à jour.');
        $this->redirect('/admin');
    }

    /**
     * Supprime un utilisateur après vérification du token CSRF.
     *
     * @route POST /admin/users/delete
     */
    public function delete(): void
    {
        Auth::requireAdmin();

        $id = (int) ($_POST['id'] ?? 0);

        if (!Session::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            $this->redirect('/admin');
            return;
        }

        $this->userModel->delete($id);
        Session::setFlash('success', 'Utilisateur supprimé.');
        $this->redirect('/admin');
    }
}
