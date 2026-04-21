<?php
require_once __DIR__ . '/../../core/BaseController.php';
require_once __DIR__ . '/../models/Message.php';
require_once __DIR__ . '/../../utils/Auth.php';
require_once __DIR__ . '/../../utils/Flash.php';
require_once __DIR__ . '/../../utils/Sanitizer.php';

class MessageController extends BaseController
{
    private Message $messageModel;

    /**
     * Initialise le contrôleur avec le modèle Message.
     */
    public function __construct()
    {
        $this->messageModel = new Message();
    }

    /**
     * Affiche la liste des conversations de l'utilisateur connecté.
     *
     * @route GET /messages
     */
    public function index(): void
    {
        Auth::requireLogin();
        $userId = Auth::currentUserId();

        $this->render('messages/index', [
            'conversations' => $this->messageModel->getConversations($userId),
            'currentUserId' => $userId,
        ], 'Messagerie');
    }

    /**
     * Affiche le fil de conversation entre l'utilisateur connecté et un partenaire.
     * Autorise l'accès uniquement si un historique existe ou si un article lie les deux parties.
     * Marque les messages reçus comme lus à l'ouverture.
     *
     * @route GET /messages/conversation?user={partnerId}[&article={articleId}]
     */
    public function conversation(): void
    {
        Auth::requireLogin();
        $userId    = Auth::currentUserId();
        $partnerId = (int) ($_GET['user'] ?? 0);
        $articleId = isset($_GET['article']) ? (int) $_GET['article'] : null;

        // Paramètre manquant ou tentative de se contacter soi-même
        if (!$partnerId || $partnerId === $userId) {
            header('Location: ' . BASE_URL . '/messages');
            exit;
        }

        // Le partenaire doit exister
        $partner = $this->messageModel->getPartner($partnerId);
        if (!$partner) {
            header('Location: ' . BASE_URL . '/messages');
            exit;
        }

        $hasHistory = $this->messageModel->hasConversation($userId, $partnerId);

        // Aucun historique : on n'accepte que si un article valide lie les deux utilisateurs
        if (!$hasHistory) {
            if (!$articleId || !$this->messageModel->articleBelongsTo($articleId, $partnerId)) {
                Flash::set('error', 'Accès non autorisé.');
                header('Location: ' . BASE_URL . '/messages');
                exit;
            }
        }

        $this->messageModel->markRead($userId, $partnerId);

        $messages = $this->messageModel->getConversation($userId, $partnerId);

        // Si aucun article dans l'URL, on le détecte depuis l'historique
        if (!$articleId) {
            foreach ($messages as $msg) {
                if (!empty($msg['article_ref_id'])) {
                    $articleId = (int) $msg['article_ref_id'];
                    break;
                }
            }
        }

        $this->render('messages/conversation', [
            'messages'      => $messages,
            'partner'       => $partner,
            'currentUserId' => $userId,
            'articleId'     => $articleId,
        ], 'Conversation avec ' . htmlspecialchars($partner['name'], ENT_QUOTES, 'UTF-8'));
    }

    /**
     * Envoie un message à un autre utilisateur.
     * Le premier message doit obligatoirement être lié à un article appartenant au destinataire.
     *
     * @route POST /messages/send
     */
    public function send(): void
    {
        Auth::requireLogin();
        $userId     = Auth::currentUserId();
        $receiverId = (int) ($_POST['receiver_id'] ?? 0);
        $body       = Sanitizer::clean($_POST['body'] ?? '');
        $articleId  = !empty($_POST['article_id']) ? (int) $_POST['article_id'] : null;

        if (!$receiverId || $receiverId === $userId || strlen($body) < 1) {
            Flash::set('error', 'Message invalide.');
            header('Location: ' . BASE_URL . '/messages');
            exit;
        }

        $hasHistory = $this->messageModel->hasConversation($userId, $receiverId);

        // Premier message : l'article doit exister et appartenir au destinataire
        if (!$hasHistory) {
            if (!$articleId || !$this->messageModel->articleBelongsTo($articleId, $receiverId)) {
                Flash::set('error', 'Action non autorisée.');
                header('Location: ' . BASE_URL . '/messages');
                exit;
            }
        }

        $this->messageModel->send($userId, $receiverId, $body, $articleId);

        $redirect = BASE_URL . '/messages/conversation?user=' . $receiverId;
        if ($articleId) {
            $redirect .= '&article=' . $articleId;
        }
        header('Location: ' . $redirect);
        exit;
    }
}
