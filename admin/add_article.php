<?php
session_start();
require '../includes/db.php';

// Vérification que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Définition du dossier d'upload des images
$uploadDir = realpath(__DIR__ . '/../assets/uploads') . DIRECTORY_SEPARATOR;
if (!is_dir($uploadDir)) {
    die("Erreur : Le dossier d'upload n'existe pas !");
}

$message = null;

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = $_POST['titre'];
    $contenu = $_POST['contenu'];
    $image = null;

    // Gestion de l'upload d'image
    if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $tmp_name = $_FILES['image']['tmp_name'];
        $extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

        $extensionsAutorisees = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($extension, $extensionsAutorisees)) {
            $nouveauNomImage = uniqid('img_', true) . '.' . $extension;
            $destPath = $uploadDir . $nouveauNomImage;

            if (!file_exists($tmp_name)) {
                die("Erreur : Le fichier temporaire n'existe pas.");
            }

            if (move_uploaded_file($tmp_name, $destPath)) {
                $image = $nouveauNomImage;
            } else {
                $message = 'Erreur lors de l\'enregistrement de l\'image.';
            }
        } else {
            $message = 'Format d\'image non autorisé (jpg, jpeg, png, gif uniquement).';
        }
    }

    if (!$message) {
        $stmt = $pdo->prepare("INSERT INTO articles (titre, contenu, image) VALUES (?, ?, ?)");
        $stmt->execute([$titre, $contenu, $image]);

        header('Location: articles.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Ajouter un article</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="articles.php">📝 Ajouter un Article</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <a href="dashboard.php" class="btn btn-secondary me-2">🏠 Tableau de bord</a>
                    <a href="articles.php" class="btn btn-primary">📚 Liste des articles</a>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-body">
                        <h3 class="card-title text-center mb-4">📝 Ajouter un Nouvel Article</h3>

                        <?php if ($message): ?>
                            <div class="alert alert-danger text-center"><?= htmlspecialchars($message) ?></div>
                        <?php endif; ?>

                        <form method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label class="form-label">Titre</label>
                                <input type="text" name="titre" class="form-control" placeholder="Titre de l'article" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Contenu</label>
                                <textarea name="contenu" class="form-control" placeholder="Contenu de l'article" rows="6" required></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Image (facultatif)</label>
                                <input type="file" name="image" class="form-control">
                                <small class="text-muted">Formats acceptés : jpg, jpeg, png, gif.</small>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="articles.php" class="btn btn-secondary">❌ Annuler</a>
                                <button type="submit" class="btn btn-success">📤 Publier l'article</button>
                            </div>
                        </form>
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