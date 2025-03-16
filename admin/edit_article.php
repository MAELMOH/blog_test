<?php
session_start();
require '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: articles.php');
    exit;
}

$id = (int)$_GET['id'];

$stmt = $pdo->prepare('SELECT * FROM articles WHERE id = ?');
$stmt->execute([$id]);
$article = $stmt->fetch();

if (!$article) {
    die('Article introuvable.');
}

$uploadDir = realpath(__DIR__ . '/../assets/uploads') . DIRECTORY_SEPARATOR;


if (!is_dir($uploadDir)) {
    die("Erreur : Le dossier d'upload n'existe pas !");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = $_POST['titre'];
    $contenu = $_POST['contenu'];
    $imageActuelle = $article['image'];

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
                $imageActuelle = $nouveauNomImage;
            } else {
                die('Erreur lors de l\'enregistrement de l\'image.');
            }
        } else {
            die('Format d\'image non autorisé (jpg, jpeg, png, gif uniquement).');
        }
    }

    $stmt = $pdo->prepare('UPDATE articles SET titre=?, contenu=?, image=? WHERE id=?');
    $stmt->execute([$titre, $contenu, $imageActuelle, $id]);

    header('Location: articles.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Modifier l'article</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container py-5">
        <h3>Modifier l'article</h3>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label>Titre</label>
                <input type="text" name="titre" value="<?= htmlspecialchars($article['titre']) ?>" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Contenu</label>
                <textarea name="contenu" class="form-control" rows="6" required><?= htmlspecialchars($article['contenu']) ?></textarea>
            </div>

            <?php if ($article['image']): ?>
                <div class="mb-3">
                    <p>Image actuelle :</p>
                    <img src="../assets/uploads/<?= htmlspecialchars($article['image']) ?>" class="img-fluid" style="max-width:200px;">
                </div>
            <?php endif; ?>

            <div class="mb-3">
                <label>Changer l'image (facultatif)</label>
                <input type="file" name="image" class="form-control">
            </div>

            <button type="submit" class="btn btn-success">Mettre à jour</button>
            <a href="articles.php" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
</body>

</html>