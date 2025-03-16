<?php
session_start();
require '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$sel = "BonjourCyber";

$message = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ancien_mot_de_passe = $_POST['ancien_mot_de_passe'];
    $nouveau_mot_de_passe = $_POST['nouveau_mot_de_passe'];
    $confirmer_mot_de_passe = $_POST['confirmer_mot_de_passe'];

    $stmt = $pdo->prepare("SELECT mot_de_passe FROM utilisateur WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $utilisateur = $stmt->fetch();

    if ($utilisateur) {
        // Vérifier l'ancien mot de passe
        $ancien_mot_de_passe_sale = $sel . $ancien_mot_de_passe;
        if (!password_verify($ancien_mot_de_passe_sale, $utilisateur['mot_de_passe'])) {
            $message = "⚠️ Ancien mot de passe incorrect.";
        } elseif ($nouveau_mot_de_passe !== $confirmer_mot_de_passe) {
            $message = "⚠️ Les nouveaux mots de passe ne correspondent pas.";
        } elseif (strlen($nouveau_mot_de_passe) < 6) {
            $message = "⚠️ Le mot de passe doit contenir au moins 6 caractères.";
        } else {
            $nouveau_mot_de_passe_sale = $sel . $nouveau_mot_de_passe;
            $nouveau_mot_de_passe_hash = password_hash($nouveau_mot_de_passe_sale, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare("UPDATE utilisateur SET mot_de_passe = ? WHERE id = ?");
            $stmt->execute([$nouveau_mot_de_passe_hash, $_SESSION['user_id']]);

            $message = "✅ Mot de passe mis à jour avec succès !";
        }
    } else {
        $message = "❌ Erreur lors de la récupération de vos informations.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Modifier le mot de passe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">📊 Tableau de bord</a>
        </div>
    </nav>

    <!-- Contenu principal -->
    <div class="container d-flex justify-content-center align-items-center" style="height: 90vh;">
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-body">
                    <h3 class="card-title text-center mb-4">🔑 Modifier le mot de passe</h3>

                    <?php if ($message): ?>
                        <div class="alert alert-info text-center"><?= htmlspecialchars($message) ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Ancien mot de passe</label>
                            <input type="password" name="ancien_mot_de_passe" class="form-control" required placeholder="Entrez votre ancien mot de passe">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nouveau mot de passe</label>
                            <input type="password" name="nouveau_mot_de_passe" class="form-control" required placeholder="Entrez un nouveau mot de passe">
                            <small class="text-muted">Minimum 6 caractères</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Confirmer le mot de passe</label>
                            <input type="password" name="confirmer_mot_de_passe" class="form-control" required placeholder="Confirmez votre mot de passe">
                        </div>

                        <button type="submit" class="btn btn-primary w-100">🔄 Modifier le mot de passe</button>
                    </form>

                    <div class="text-center mt-3">
                        <a href="dashboard.php" class="text-decoration-none">🔙 Retour au tableau de bord</a>
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