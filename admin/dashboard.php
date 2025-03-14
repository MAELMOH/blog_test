<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
?>

<html>

<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-3">
        <h3>Espace Administrateur</h3>
        <a href="articles.php" class="btn btn-primary">Gérer les articles</a>
        <a href="logout.php" class="btn btn-danger">Déconnexion</a>
    </div>