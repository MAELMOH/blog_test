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

$stmt = $pdo->prepare('SELECT image FROM articles WHERE id = ?');
$stmt->execute([$id]);
$article = $stmt->fetch();

if (!$article) {
    die('Article introuvable.');
}

if (!empty($article['image'])) {
    $imagePath = realpath(__DIR__ . '/../assets/uploads/' . $article['image']);
    
    if (file_exists($imagePath) && is_file($imagePath)) {
        unlink($imagePath);
    }
}

$stmt = $pdo->prepare('DELETE FROM articles WHERE id = ?');
$stmt->execute([$id]);

header('Location: articles.php');
exit;
