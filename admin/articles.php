<?php
session_start();
require '../includes/db.php';

// Vérification que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Récupérer la liste des articles
$stmt = $pdo->query('SELECT * FROM articles ORDER BY date_publication DESC');
$articles = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Gestion des articles</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container py-5">
        <h2 class="mb-4">📑 Gestion des Articles</h2>

        <div class="mb-3">
            <a href="add_article.php" class="btn btn-success">➕ Ajouter un article</a>
            <a href="dashboard.php" class="btn btn-secondary">🏠 Retour au tableau de bord</a>
        </div>

        <?php if (empty($articles)): ?>
            <div class="alert alert-info">Aucun article trouvé.</div>
        <?php else: ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Titre</th>
                        <th>Date de publication</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($articles as $article): ?>
                        <tr>
                            <td>
                                <?php if (!empty($article['image'])): ?>
                                    <img src="../assets/uploads/<?= htmlspecialchars($article['image']) ?>"
                                        class="img-fluid" style="max-width: 80px; height: auto;">
                                <?php else: ?>
                                    <span class="text-muted">Aucune image</span>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($article['titre']) ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($article['date_publication'])) ?></td>
                            <td>
                                <a href="edit_article.php?id=<?= $article['id'] ?>" class="btn btn-sm btn-primary">✏️ Modifier</a>
                                <a href="delete_article.php?id=<?= $article['id'] ?>"
                                    class="btn btn-sm btn-danger"
                                    onclick="return confirm('Voulez-vous vraiment supprimer cet article ?')">🗑️ Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

    </div>

</body>

</html>