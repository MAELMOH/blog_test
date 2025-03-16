<?php
require '../includes/db.php';

// Récupérer tous les articles publiés
$stmt = $pdo->query("SELECT * FROM articles ORDER BY date_publication DESC");
$articles = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Blog - Accueil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">📖 Mon Blog</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="../admin/login.php">🔑 Admin</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h1 class="text-center mb-4">📚 Articles Récents</h1>

        <?php if (empty($articles)): ?>
            <div class="alert alert-info text-center">Aucun article disponible pour le moment.</div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($articles as $article): ?>
                    <div class="col-md-4">
                        <div class="card shadow-sm mb-4">
                            <?php if (!empty($article['image'])): ?>
                                <img src="../assets/uploads/<?= htmlspecialchars($article['image']) ?>" class="card-img-top" alt="Image de l'article">
                            <?php else: ?>
                                <img src="../assets/uploads/default.jpg" class="card-img-top" alt="Image par défaut">
                            <?php endif; ?>
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($article['titre']) ?></h5>
                                <p class="text-muted"><?= date('d/m/Y H:i', strtotime($article['date_publication'])) ?></p>
                                <p class="card-text"><?= substr(htmlspecialchars($article['contenu']), 0, 100) ?>...</p>
                                <a href="article.php?id=<?= $article['id'] ?>" class="btn btn-primary">Lire plus 📖</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <footer class="bg-dark text-white text-center py-3 mt-5">
        &copy; <?= date('Y') ?> Mon Blog | Tous droits réservés
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>