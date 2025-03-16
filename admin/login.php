<?php
session_start();
require '../includes/db.php';

$sel = "BonjourCyber";

$error_message = $_SESSION['error_message'] ?? null;
unset($_SESSION['error_message']);

if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

$erreur = null;

// Traitement du formulaire de connexion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['email']) && !empty($_POST['mot_de_passe'])) {
        $stmt = $pdo->prepare('SELECT * FROM utilisateur WHERE email = ?');
        $stmt->execute([$_POST['email']]);
        $utilisateur = $stmt->fetch();

        $mot_de_passe_sale = $sel . $_POST['mot_de_passe'];

        if ($utilisateur && password_verify($mot_de_passe_sale, $utilisateur['mot_de_passe'])) {
            $_SESSION['user_id'] = $utilisateur['id'];
            header('Location: dashboard.php');
            exit;
        } else {
            $erreur = "⚠️ Email ou mot de passe incorrect.";
        }
    } else {
        $erreur = "⚠️ Veuillez remplir tous les champs.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Connexion - Administrateur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="../public/index.php">🏠 Accueil</a>
        </div>
    </nav>

    <!-- Contenu principal -->
    <div class="container d-flex justify-content-center align-items-center" style="height: 90vh;">
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-body">
                    <h3 class="card-title text-center mb-4">🔑 Connexion Admin</h3>

                    <?php if ($erreur): ?>
                        <div class="alert alert-danger text-center"><?= htmlspecialchars($erreur) ?></div>
                    <?php endif; ?>

                    <?php if ($error_message): ?>
                        <div class="alert alert-danger text-center"><?= htmlspecialchars($error_message) ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required placeholder="Entrez votre email">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mot de passe</label>
                            <input type="password" name="mot_de_passe" class="form-control" required placeholder="Entrez votre mot de passe">
                        </div>

                        <button type="submit" class="btn btn-primary w-100">🔐 Se connecter</button>
                    </form>

                    <div class="text-center mt-3">
                        <a href="password_forgot.php" class="text-decoration-none">🔁 Mot de passe oublié ?</a>
                    </div>

                    <div class="text-center mt-4">
                        <a href="../public/index.php" class="btn btn-outline-secondary w-100">🏠 Retour à l'accueil</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pied de page -->
    <footer class="bg-dark text-white text-center py-3">
        &copy; <?= date('Y') ?> Mon Blog | Tous droits réservés
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>