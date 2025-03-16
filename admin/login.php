<?php
session_start();
require '../includes/db.php';

$sel = "BonjourCyber";

if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

$erreur = null;

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
            $erreur = "Email ou mot de passe incorrect.";
        }
    } else {
        $erreur = "Veuillez remplir tous les champs.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion Administrateur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container" style="max-width: 400px; margin-top: 100px;">
    <div class="card shadow">
        <div class="card-body">
            <h4 class="card-title text-center mb-4">🔑 Connexion Admin</h4>

            <?php if ($erreur): ?>
                <div class="alert alert-danger text-center"><?= htmlspecialchars($erreur) ?></div>
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

</body>
</html>
