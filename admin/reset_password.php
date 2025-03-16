<?php
session_start();
require '../includes/db.php';

$sel = "BonjourCyber";

if (!isset($_GET['token']) || empty($_GET['token'])) {
    $_SESSION['error_message'] = "❌ Token invalide ou expiré.";
    header('Location: login.php');
    exit;
}

$token = $_GET['token'];
$message = null;

// Vérifier si le token existe en base de données
$stmt = $pdo->prepare("SELECT * FROM reinitialisations_mdp WHERE token = ? LIMIT 1");
$stmt->execute([$token]);
$resetEntry = $stmt->fetch();

if (!$resetEntry) {
    $_SESSION['error_message'] = "❌ Lien de réinitialisation invalide ou expiré.";
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['mot_de_passe']) && !empty($_POST['confirmer_mot_de_passe'])) {
        $mot_de_passe = $_POST['mot_de_passe'];
        $confirmer_mot_de_passe = $_POST['confirmer_mot_de_passe'];

        if ($mot_de_passe !== $confirmer_mot_de_passe) {
            $message = "⚠️ Les mots de passe ne correspondent pas.";
        } elseif (strlen($mot_de_passe) < 6) {
            $message = "⚠️ Le mot de passe doit contenir au moins 6 caractères.";
        } else {
            $mot_de_passe_sale = $sel . $mot_de_passe;
            $mot_de_passe_hash = password_hash($mot_de_passe_sale, PASSWORD_DEFAULT);

            // Mettre à jour le mot de passe de l'utilisateur
            $stmt = $pdo->prepare("UPDATE utilisateur SET mot_de_passe = ? WHERE email = ?");
            $stmt->execute([$mot_de_passe_hash, $resetEntry['email']]);

            // Supprimer le token après la réinitialisation
            $stmt = $pdo->prepare("DELETE FROM reinitialisations_mdp WHERE email = ?");
            $stmt->execute([$resetEntry['email']]);

            $message = "✅ Votre mot de passe a été réinitialisé avec succès !";
        }
    } else {
        $message = "⚠️ Veuillez remplir tous les champs.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Réinitialisation du mot de passe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="../public/index.php">🏠 Accueil</a>
            <div class="ms-auto">
                <a href="login.php" class="btn btn-outline-light">🔑 Connexion</a>
            </div>
        </div>
    </nav>

    <!-- Contenu principal -->
    <div class="container d-flex justify-content-center align-items-center" style="height: 90vh;">
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-body">
                    <h3 class="card-title text-center mb-4">🔑 Réinitialisation du mot de passe</h3>

                    <?php if ($message): ?>
                        <div class="alert alert-info text-center"><?= htmlspecialchars($message) ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Nouveau mot de passe</label>
                            <input type="password" name="mot_de_passe" class="form-control" required placeholder="Entrez un nouveau mot de passe">
                            <small class="text-muted">Minimum 6 caractères</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Confirmer le mot de passe</label>
                            <input type="password" name="confirmer_mot_de_passe" class="form-control" required placeholder="Confirmez votre mot de passe">
                        </div>

                        <button type="submit" class="btn btn-primary w-100">🔄 Mettre à jour</button>
                    </form>

                    <div class="text-center mt-3">
                        <a href="login.php" class="text-decoration-none">🔙 Retour à la connexion</a>
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