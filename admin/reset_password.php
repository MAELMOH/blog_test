<?php
session_start();
require '../includes/db.php';

$sel = "BonjourCyber";

if (!isset($_GET['token']) || empty($_GET['token'])) {
    die("Token invalide.");
}

$token = $_GET['token'];
$message = null;

$stmt = $pdo->prepare("SELECT * FROM reinitialisations_mdp WHERE token = ? LIMIT 1");
$stmt->execute([$token]);
$resetEntry = $stmt->fetch();

if (!$resetEntry) {
    die("Lien de réinitialisation invalide ou expiré.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['mot_de_passe']) && !empty($_POST['confirmer_mot_de_passe'])) {
        $mot_de_passe = $_POST['mot_de_passe'];
        $confirmer_mot_de_passe = $_POST['confirmer_mot_de_passe'];

        if ($mot_de_passe === $confirmer_mot_de_passe) {
            $mot_de_passe_sale = $sel . $mot_de_passe;
            $mot_de_passe_hash = password_hash($mot_de_passe_sale, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare("UPDATE utilisateur SET mot_de_passe = ? WHERE email = ?");
            $stmt->execute([$mot_de_passe_hash, $resetEntry['email']]);

            $stmt = $pdo->prepare("DELETE FROM reinitialisations_mdp WHERE email = ?");
            $stmt->execute([$resetEntry['email']]);

            $message = "✅ Votre mot de passe a été réinitialisé avec succès ! <a href='login.php'>Connexion</a>";
        } else {
            $message = "⚠️ Les mots de passe ne correspondent pas.";
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
    <title>Réinitialiser le mot de passe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container" style="max-width: 400px; margin-top: 100px;">
    <div class="card shadow">
        <div class="card-body">
            <h4 class="card-title text-center mb-4">🔑 Réinitialisation du mot de passe</h4>

            <?php if ($message): ?>
                <div class="alert alert-info text-center"><?= $message ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Nouveau mot de passe</label>
                    <input type="password" name="mot_de_passe" class="form-control" required placeholder="Entrez un nouveau mot de passe">
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

</body>
</html>
