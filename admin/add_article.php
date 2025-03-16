<?php
session_start();
require '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$uploadDir = realpath(__DIR__ . '/../assets/uploads') . DIRECTORY_SEPARATOR;

if (!is_dir($uploadDir)) {
    die("Erreur : Le dossier d'upload n'existe pas !");
}

$message = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = $_POST['titre'];
    $contenu = $_POST['contenu'];
    $image = null;

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
                die('Erreur lors de l\'enregistrement de l\'image.');
            }
        } else {
            die('Format d\'image non autorisé (jpg, jpeg, png, gif uniquement).');
        }
    }

    $stmt = $pdo->prepare("INSERT INTO articles (titre, contenu, image) VALUES (?, ?, ?)");
    $stmt->execute([$titre, $contenu, $image]);

    header('Location: articles.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Ajouter un article</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container py-5">
        <h2>Ajouter un nouvel article</h2>

        <?php if ($message): ?>
            <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <input type="text" name="titre" class="form-control" placeholder="Titre" required>
            </div>

            <div class="mb-3">
                <textarea name="contenu" class="form-control" placeholder="Contenu de l'article" rows="6" required></textarea>
            </div>

            <div class="mb-3">
                <input type="file" name="image" class="form-control">
                <small class="text-muted">Formats acceptés : jpg, jpeg, png, gif.</small>
            </div>

            <button type="submit" class="btn btn-success">Publier l'article</button>
            <a href="articles.php" class="btn btn-secondary">Retour aux articles</a>
        </form>
    </div>
</body>

</html>