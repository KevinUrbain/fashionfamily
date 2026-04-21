<?php
require_once __DIR__ . '/../../../core/BaseController.php';
require_once __DIR__ . '/../../../utils/Auth.php';
require_once __DIR__ . '/../../../utils/Validator.php';
require_once __DIR__ . '/../../../utils/Sanitizer.php';
require_once __DIR__ . '/../../models/Article.php';

class AdminProductController extends BaseController
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
     * Affiche le formulaire d'édition d'un article.
     *
     * @route GET /admin/articles/edit?id={id}
     */
    public function edit(): void
    {
        Auth::requireAdmin();

        $id      = (int) ($_GET['id'] ?? 0);
        $article = $this->articleModel->getById($id);

        if (!$article) {
            $this->redirect('/admin');
            return;
        }

        $this->renderAdmin('admin/articles/edit', [
            'article' => $article,
        ], 'Modifier article');
    }

    /**
     * Traite la mise à jour des informations d'un article.
     *
     * @route POST /admin/articles/edit
     */
    public function update(): void
    {
        Auth::requireAdmin();

        $id = (int) ($_POST['id'] ?? 0);

        if (!Session::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            Session::setFlash('error', 'Session expirée, veuillez réessayer.');
            $this->redirect('/admin/articles/edit?id=' . $id);
            return;
        }

        $article = $this->articleModel->getById($id);
        if (!$article) {
            $this->redirect('/admin');
            return;
        }

        $data = [
            'title'             => Sanitizer::clean($_POST['title'] ?? ''),
            'description'       => Sanitizer::clean($_POST['description'] ?? ''),
            'price'             => (float) ($_POST['price'] ?? 0),
            'quantity'          => (int) ($_POST['quantity'] ?? 0),
            'article_condition' => Sanitizer::clean($_POST['article_condition'] ?? ''),
            'status'            => in_array($_POST['status'] ?? '', ['active', 'inactive']) ? $_POST['status'] : 'inactive',
        ];

        $validator = new Validator();
        $errors    = $validator->validate(
            ['title' => $data['title'], 'price' => (string) $data['price']],
            ['title' => ['required'], 'price' => ['required']]
        );

        if ($validator->hasErrors()) {
            $this->renderAdmin('admin/articles/edit', [
                'article' => $article,
                'errors'  => Validator::flattenErrors($errors),
            ], 'Modifier article');
            return;
        }

        $this->articleModel->update($id, $data);
        Session::setFlash('success', 'Article mis à jour.');
        $this->redirect('/admin');
    }

    /**
     * Supprime un article après vérification du token CSRF.
     *
     * @route POST /admin/articles/delete
     */
    public function delete(): void
    {
        Auth::requireAdmin();

        $id = (int) ($_POST['id'] ?? 0);

        if (!Session::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            $this->redirect('/admin');
            return;
        }

        $this->articleModel->delete($id);
        Session::setFlash('success', 'Article supprimé.');
        $this->redirect('/admin');
    }
}
