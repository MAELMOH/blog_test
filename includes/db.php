<?php

try {
    $pdo = new PDO('mysql:host=localhost;dbname=blog_test;charset=utf8', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException) {
    die("Erreur : " . $e->getMessage());
}