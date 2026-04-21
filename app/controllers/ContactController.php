<?php
require_once __DIR__ . '/../../core/BaseController.php';
require_once __DIR__ . '/../../utils/Sanitizer.php';
require_once __DIR__ . '/../../utils/Flash.php';

class ContactController extends BaseController
{
    /**
     * Affiche la page de contact.
     *
     * @route GET /home/contact
     */
    public function index(): void
    {
        $this->render('home/contact', [
            'description' => APP_NAME . ' - Contactez-nous',
        ], 'Contact');
    }

    /**
     * Traite la soumission du formulaire de contact.
     * Valide les champs, puis envoie un email à l'adresse CONTACT_EMAIL.
     *
     * @route POST /home/contact
     */
    public function send(): void
    {
        $name = Sanitizer::clean($_POST['name'] ?? '');
        $email = Sanitizer::clean($_POST['email'] ?? '');
        $subject = Sanitizer::clean($_POST['subject'] ?? '');
        $message = Sanitizer::clean($_POST['message'] ?? '');

        $errors = [];

        if (empty($name)) {
            $errors[] = 'Le nom est requis.';
        }

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Adresse email invalide.';
        }

        if (empty($subject)) {
            $errors[] = 'Le sujet est requis.';
        }

        if (strlen($message) < 10) {
            $errors[] = 'Le message doit contenir au moins 10 caractères.';
        }

        if (!empty($errors)) {
            Flash::set('error', implode(' ', $errors));
            header('Location: ' . BASE_URL . '/home/contact');
            exit;
        }

        $to = CONTACT_EMAIL;
        $headers = implode("\r\n", [
            'From: ' . $name . ' <' . $email . '>',
            'Reply-To: ' . $email,
            'Content-Type: text/plain; charset=UTF-8',
            'MIME-Version: 1.0',
        ]);

        $body = "Nom : $name\nEmail : $email\n\n$message";

        if (mail($to, $subject, $body, $headers)) {
            Flash::set('success', 'Votre message a bien été envoyé. Nous vous répondrons dans les plus brefs délais.');
        } else {
            Flash::set('error', "Une erreur est survenue lors de l'envoi. Veuillez réessayer.");
        }

        header('Location: ' . BASE_URL . '/home/contact');
        exit;
    }
}
