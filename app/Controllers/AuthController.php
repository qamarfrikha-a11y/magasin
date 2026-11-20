<?php
class AuthController extends Controller
{
    // GET : formulaire de connexion
    public function login(): void
    {
        $this->render('auth/login', ['title' => 'Connexion', 'email' => '']);
    }

    // POST : vérifie les identifiants
    public function authenticate(): void
    {
        $this->requirePost();
        $email = trim($_POST['email'] ?? '');
        $user  = (new UtilisateurModel())->findByEmail($email);

        // Même message dans tous les cas d'échec : on ne révèle pas si l'email existe
        if ($user === null || !password_verify($_POST['mot_de_passe'] ?? '', $user['mot_de_passe'])) {
            $this->render('auth/login', ['title' => 'Connexion', 'email' => $email, 'error' => 'Email ou mot de passe incorrect.']);
            return;
        }

        Auth::login($user);
        $this->flash('success', 'Bienvenue ' . $user['nom'] . ' !');
        $this->redirect($user['role'] === 'admin' ? url('admin') : url('catalogue'));
    }

    public function register(): void
    {
        $this->render('auth/register', ['title' => 'Inscription', 'old' => ['nom' => '', 'email' => ''], 'errors' => []]);
    }

    public function store(): void
    {
        $this->requirePost();
        $users = new UtilisateurModel();
        $old = ['nom' => trim($_POST['nom'] ?? ''), 'email' => trim($_POST['email'] ?? '')];
        $mdp = $_POST['mot_de_passe'] ?? '';

        $errors = [];
        if ($old['nom'] === '' || mb_strlen($old['nom']) > 100) {
            $errors['nom'] = 'Nom obligatoire (100 caractères maximum).';
        }
        if (filter_var($old['email'], FILTER_VALIDATE_EMAIL) === false) {
            $errors['email'] = 'Adresse email invalide.';
        } elseif ($users->findByEmail($old['email']) !== null) {
            $errors['email'] = 'Cet email est déjà utilisé.';
        }
        if (strlen($mdp) < 6) {
            $errors['mot_de_passe'] = 'Le mot de passe doit contenir au moins 6 caractères.';
        }

        if ($errors) {
            $this->render('auth/register', ['title' => 'Inscription', 'old' => $old, 'errors' => $errors]);
            return;
        }

        // Le rôle est TOUJOURS 'client' : il n'est jamais lu depuis le formulaire
        $id = $users->create([
            'nom' => $old['nom'], 'email' => $old['email'],
            'mot_de_passe' => password_hash($mdp, PASSWORD_DEFAULT), 'role' => 'client',
        ]);
        Auth::login($users->find($id));
        $this->flash('success', 'Compte créé. Bienvenue !');
        $this->redirect(url('catalogue'));
    }

    // POST : la déconnexion modifie l'état, donc POST + jeton CSRF
    public function logout(): void
    {
        $this->requirePost();
        Auth::logout();
        session_start();   // nouvelle session pour afficher le message
        $this->flash('success', 'Vous êtes déconnecté.');
        $this->redirect(url('catalogue'));
    }
}
