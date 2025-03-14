<?php

session_start();
require('../includes/db.php');

if (isset($_POST['email'], $_POST['password'])) {
    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?");
    $stmt->execute([$_POST['email']]);
    $user = $stmt->fetch();

    if ($user && password_verify($_POST['password'], $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header('Location: dashboard.php');
        exit;
    } else {
        $erreur = "Identifiants incorrects.";
    }
}
?>


<html>

<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5/dist/css/bootstrap.min.css">
</head>

<body class="container">
    <h2>Connexion Admin</h2>
    <?php if (isset($erreur)): ?>
        <div class="alert alert-danger"><?= $erreur ?></div>
    <?php endif; ?>
    <form method="POST">
        <input type="email" class="form-control" placeholder="Email" name="email" required>
        <input type="password" class="form-control mt-2" placeholder="Mot de passe" name="password" required>
        <button class="btn btn-primary mt-3">Connexion</button>
    </form>
</body>

</html>