<?php
require '../includes/db.php';

$email = "admin@admin.com";
$mot_de_passe_en_clair = "admin123";
$sel = "BonjourCyber";

$mot_de_passe_sale = $sel . $mot_de_passe_en_clair;

$mot_de_passe_hash = password_hash($mot_de_passe_sale, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("INSERT INTO utilisateur (email, mot_de_passe) VALUES (?, ?)");
$stmt->execute([$email, $mot_de_passe_hash]);

echo "Utilisateur administrateur créé avec succès.";
