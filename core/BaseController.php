<?php
class BaseController
{
    /**
     * Render une vue dans le layout principal
     */
    protected function render(string $view, array $data = [], string $title = 'FashionFamily'): void
    {
        extract($data);

        ob_start();
        require __DIR__ . '/../app/views/' . $view . '.php';
        $content = ob_get_clean();

        require __DIR__ . '/../app/views/layout.php';
    }

    /**
     * Render une vue dans le layout admin
     */
    protected function renderAdmin(string $view, array $data = [], string $title = 'Admin - FashionFamily'): void
    {
        extract($data);

        ob_start();
        require __DIR__ . '/../app/views/' . $view . '.php';
        $content = ob_get_clean();

        require __DIR__ . '/../app/views/admin/layout.php';
    }

    /**
     * Render une vue partielle (sans layout) — pour les réponses AJAX
     */
    protected function renderPartial(string $view, array $data = []): void
    {
        extract($data);
        require __DIR__ . '/../app/views/' . $view . '.php';
    }

    /**
     * Redirige vers un chemin (relatif à BASE_URL)
     */
    protected function redirect(string $path): void
    {
        header('Location: ' . BASE_URL . $path);
        exit;
    }

    /**
     * Retourne une réponse JSON
     */
    protected function json(mixed $data): void
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
