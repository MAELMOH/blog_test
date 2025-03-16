<?php
require '../includes/db.php';

// Vérification que l'ID de l'article est bien fourni
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$id = (int)$_GET['id'];

// Récupération de l'article depuis la base de données
$stmt = $pdo->prepare("SELECT * FROM articles WHERE id = ?");
$stmt->execute([$id]);
$article = $stmt->fetch();

if (!$article) {
    die("❌ Article introuvable.");
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($article['titre']) ?> - Mon Blog</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">🏠 Mon Blog</a>
        </div>
    </nav>

    <!-- Contenu principal -->
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <?php if (!empty($article['image'])): ?>
                        <img src="../assets/uploads/<?= htmlspecialchars($article['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($article['titre']) ?>">
                    <?php endif; ?>

                    <div class="card-body">
                        <h1 class="card-title"><?= htmlspecialchars($article['titre']) ?></h1>
                        <p class="text-muted">🕒 Publié le <?= date('d/m/Y H:i', strtotime($article['date_publication'])) ?></p>
                        <hr>
                        <p class="card-text" style="font-size: 1.1rem;"><?= nl2br(htmlspecialchars($article['contenu'])) ?></p>
                    </div>
                </div>

                <div class="mt-4 text-center">
                    <a href="index.php" class="btn btn-secondary">⬅️ Retour aux articles</a>
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