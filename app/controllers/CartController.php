<?php
require_once __DIR__ . '/../../core/BaseController.php';
require_once __DIR__ . '/../models/Cart.php';
require_once __DIR__ . '/../models/Article.php';
require_once __DIR__ . '/../models/Order.php';

class CartController extends BaseController
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
     * Affiche le contenu du panier de l'utilisateur.
     *
     * @route GET /cart
     */
    public function index(): void
    {
        $this->render('cart/index', [
            'items' => Cart::getItems(),
            'total' => Cart::getTotal(),
            'description' => APP_NAME . ' - Mon panier',
        ], APP_NAME . ' - Mon panier');
    }

    /**
     * Ajoute un article au panier après validation du token CSRF et du stock disponible.
     *
     * @route POST /cart/add
     */
    public function add(): void
    {
        if (!Session::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            Flash::set('error', 'Token CSRF invalide.');
            $this->redirect('/products');
            return;
        }

        $id = (int) ($_POST['article_id'] ?? 0);
        $quantity = max(1, (int) ($_POST['quantity'] ?? 1));

        if ($id <= 0) {
            Flash::set('error', 'Article invalide.');
            $this->redirect('/products');
            return;
        }

        $article = $this->articleModel->getById($id);

        if (!$article) {
            Flash::set('error', 'Article introuvable.');
            $this->redirect('/products');
            return;
        }

        if ((int) $article['quantity'] <= 0) {
            Flash::set('error', 'Cet article n\'est plus en stock.');
            $this->redirect('/products/show?id=' . $id);
            return;
        }

        Cart::add($article, $quantity);
        Flash::set('success', '« ' . $article['title'] . ' » a été ajouté à votre panier.');

        $redirect = $_POST['redirect'] ?? '/cart';
        $this->redirect($redirect);
    }

    /**
     * Modifie la quantité d'un article dans le panier.
     * Supprime l'article si la quantité est inférieure ou égale à zéro.
     *
     * @route POST /cart/update
     */
    public function update(): void
    {
        if (!Session::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            Flash::set('error', 'Token CSRF invalide.');
            $this->redirect('/cart');
            return;
        }

        $id = (int) ($_POST['article_id'] ?? 0);
        $quantity = (int) ($_POST['quantity'] ?? 0);

        if ($id <= 0) {
            Flash::set('error', 'Article invalide.');
            $this->redirect('/cart');
            return;
        }

        Cart::update($id, $quantity);
        Flash::set('success', 'Panier mis à jour.');
        $this->redirect('/cart');
    }

    /**
     * Retire un article du panier.
     *
     * @route POST /cart/remove
     */
    public function remove(): void
    {
        if (!Session::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            Flash::set('error', 'Token CSRF invalide.');
            $this->redirect('/cart');
            return;
        }

        $id = (int) ($_POST['article_id'] ?? 0);

        if ($id <= 0) {
            Flash::set('error', 'Article invalide.');
            $this->redirect('/cart');
            return;
        }

        Cart::remove($id);
        Flash::set('success', 'Article retiré du panier.');
        $this->redirect('/cart');
    }

    /**
     * Convertit le panier en commande et vide le panier après confirmation.
     * Requiert que l'utilisateur soit connecté et que le panier ne soit pas vide.
     *
     * @route POST /cart/checkout
     */
    public function checkout(): void
    {
        Auth::requireLogin();

        if (!Session::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            Flash::set('error', 'Token CSRF invalide.');
            $this->redirect('/cart');
            return;
        }

        if (Cart::isEmpty()) {
            Flash::set('error', 'Votre panier est vide.');
            $this->redirect('/cart');
            return;
        }

        $items   = Cart::getItems();
        $total   = Cart::getTotal();
        $buyerId = Auth::currentUserId();

        $orderModel = new Order();
        $orderId    = $orderModel->create($buyerId, array_values($items), $total);

        Cart::clear();
        Flash::set('success', 'Commande #' . $orderId . ' passée avec succès ! Nous vous recontacterons sous peu.');
        $this->redirect('/cart');
    }

    /**
     * Vide entièrement le panier de l'utilisateur.
     *
     * @route POST /cart/clear
     */
    public function clear(): void
    {
        if (!Session::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            Flash::set('error', 'Token CSRF invalide.');
            $this->redirect('/cart');
            return;
        }

        Cart::clear();
        Flash::set('success', 'Votre panier a été vidé.');
        $this->redirect('/cart');
    }
}
