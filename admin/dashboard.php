<?php
session_start();
require '../includes/db.php';

// Vérification sécurité
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Récupération infos utilisateur connecté (optionnel)
$stmt = $pdo->prepare('SELECT email FROM utilisateur WHERE id = ?');
$stmt->execute([$_SESSION['user_id']]);
$utilisateur = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Tableau de bord administrateur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container py-5">
        <h1 class="mb-4">Tableau de bord administrateur</h1>
        <p>Bienvenue,</p>

        <div class="list-group">
            <a href="articles.php" class="list-group-item list-group-item-action">📝 Gestion des articles</a>
            <a href="modifier_mot_de_passe.php" class="list-group-item">Réinitialiser mot de passe</a>
            <a href="logout.php" class="list-group-item text-danger">Déconnexion</a>
        </div>
    </div>
</body>

</html>