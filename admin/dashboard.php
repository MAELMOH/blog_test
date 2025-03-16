<?php
session_start();
require '../includes/db.php';

// Vérification de la connexion de l'administrateur
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Récupération des informations de l'utilisateur connecté
$stmt = $pdo->prepare('SELECT email FROM utilisateur WHERE id = ?');
$stmt->execute([$_SESSION['user_id']]);
$utilisateur = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Tableau de bord - Administrateur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">📊 Tableau de bord</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <span class="nav-link text-white">👤 <?= htmlspecialchars($utilisateur['email']) ?></span>
                    </li>
                    <li class="nav-item">
                        <a href="logout.php" class="nav-link text-danger">🚪 Déconnexion</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Contenu principal -->
    <div class="container py-5">
        <h2 class="text-center mb-4">📌 Tableau de Bord Administrateur</h2>
        <p class="text-center">Bienvenue, <strong><?= htmlspecialchars($utilisateur['email']) ?></strong> 👋</p>

        <div class="row">
            <!-- Gestion des articles -->
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title">📝 Gestion des Articles</h5>
                        <p class="card-text">Créer, modifier ou supprimer des articles.</p>
                        <a href="articles.php" class="btn btn-primary w-100">Accéder</a>
                    </div>
                </div>
            </div>

            <!-- Modification du mot de passe -->
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title">🔑 Changer le mot de passe</h5>
                        <p class="card-text">Mettez à jour votre mot de passe pour plus de sécurité.</p>
                        <a href="modifier_mot_de_passe.php" class="btn btn-warning w-100">Modifier</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pied de page -->
    <footer class="bg-dark text-white text-center py-3 mt-5">
        &copy; <?= date('Y') ?> Mon Blog | Tous droits réservés
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>