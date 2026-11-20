<?php
// Classe mère de tous les contrôleurs : outils communs
abstract class Controller
{
    // Affiche une vue entre le header et le footer
    protected function render(string $view, array $data = []): void
    {
        // Données communes à toutes les vues (barre de navigation, messages)
        $data['user']      = Auth::user();
        $data['cartCount'] = Panier::count();
        $data['flash']     = $this->pullFlash();

        extract($data);   // ['produits' => [...]] devient $produits dans la vue
        require '../app/Views/layouts/header.php';
        require '../app/Views/' . $view . '.php';
        require '../app/Views/layouts/footer.php';
    }

    protected function redirect(string $url): void
    {
        header('Location: ' . $url);
        exit;
    }

    // Message affiché une seule fois à la prochaine page
    protected function flash(string $type, string $message): void
    {
        $_SESSION['flash'] = [$type, $message];
    }

    private function pullFlash(): ?array
    {
        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);
        return $flash;
    }

    // Réponse JSON (pour l'API)
    protected function json($data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    // Refuse l'accès sans le bon rôle
    protected function requireRole(string $role): void
    {
        if (!Auth::check()) {
            $this->flash('warning', 'Veuillez vous connecter.');
            $this->redirect(url('auth', 'login'));
        }
        if (!Auth::hasRole($role)) {
            http_response_code(403);
            exit('Accès interdit');
        }
    }

    protected function requireLogin(): void
    {
        if (!Auth::check()) {
            $this->flash('warning', 'Veuillez vous connecter.');
            $this->redirect(url('auth', 'login'));
        }
    }

    // Les actions qui modifient la base n'acceptent que POST
    protected function requirePost(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit('Méthode non autorisée');
        }
    }
}
