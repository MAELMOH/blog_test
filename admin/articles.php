<?php
session_start();
require '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$stmt = $pdo->query('SELECT * FROM articles ORDER BY date_publication DESC');
$articles = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Gestion des articles</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">📑 Gestion des Articles</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a href="add_article.php" class="btn btn-success me-2">➕ Ajouter un article</a>
                        <a href="dashboard.php" class="btn btn-secondary">🏠 Retour au tableau de bord</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h2 class="text-center mb-4">📚 Liste des Articles</h2>

        <?php if (empty($articles)): ?>
            <div class="alert alert-info text-center">Aucun article trouvé.</div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($articles as $article): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card shadow-sm mb-4">
                            <?php if (!empty($article['image'])): ?>
                                <img src="../assets/uploads/<?= htmlspecialchars($article['image']) ?>" class="card-img-top" alt="Image de l'article">
                            <?php else: ?>
                                <img src="../assets/uploads/default.jpg" class="card-img-top" alt="Image par défaut">
                            <?php endif; ?>
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($article['titre']) ?></h5>
                                <p class="text-muted">🕒 <?= date('d/m/Y H:i', strtotime($article['date_publication'])) ?></p>
                                <div class="d-flex justify-content-between">
                                    <a href="edit_article.php?id=<?= $article['id'] ?>" class="btn btn-primary btn-sm">✏️ Modifier</a>
                                    <a href="delete_article.php?id=<?= $article['id'] ?>"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Voulez-vous vraiment supprimer cet article ?')">🗑️ Supprimer</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Pied de page -->
    <footer class="bg-dark text-white text-center py-3 mt-5">
        &copy; <?= date('Y') ?> Mon Blog | Tous droits réservés
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>