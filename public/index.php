<?php
require '../includes/db.php';

$req = $pdo->query("SELECT * FROM articles ORDER BY date_publication DESC");
$articles = $req->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Blog</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container py-5">
        <h1 class="mb-4">Bienvenue sur mon Blog</h1>

        <?php if (empty($articles)): ?>
            <p class="alert alert-info">Aucun article publié pour le moment.</p>
        <?php else: ?>
            <?php foreach ($articles as $article): ?>
                <div class="card mb-4 shadow-sm">
                    <div class="card-body">
                        <h3 class="card-title">
                            <?= htmlspecialchars($article['titre']) ?>
                        </h3>

                        <?php if (!empty($article['image'])): ?>
                            <img src="../assets/uploads/<?= htmlspecialchars($article['image']) ?>" class="img-fluid mb-3" alt="Image de l'article">
                        <?php endif; ?>

                        <p class="card-text">
                            <?= nl2br(htmlspecialchars($article['contenu'])) ?>
                        </p>
                        <small class="text-muted">Publié le <?= date('d/m/Y à H:i', strtotime($article['date_publication'])) ?></small>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>