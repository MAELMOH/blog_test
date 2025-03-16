<?php
session_start();
require '../includes/db.php';
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Configuration SMTP
$SMTP_HOST = "smtp.office365.com";
$SMTP_USER = "********@outlook.fr";
$SMTP_PASS = "*************";
$SMTP_PORT = 587;

$message = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    // Vérifier si l'email existe dans la base de données
    $stmt = $pdo->prepare('SELECT * FROM utilisateur WHERE email = ?');
    $stmt->execute([$email]);
    $utilisateur = $stmt->fetch();

    if ($utilisateur) {
        // Générer un token unique
        $token = bin2hex(random_bytes(50));

        // Insérer ou mettre à jour le token dans la base de données
        $stmt = $pdo->prepare("INSERT INTO reinitialisations_mdp (email, token, date_creation) 
                               VALUES (?, ?, NOW()) 
                               ON DUPLICATE KEY UPDATE token = VALUES(token), date_creation = NOW()");
        $stmt->execute([$email, $token]);

        // Construire le lien de réinitialisation
        $reset_link = "http://localhost/projet_blog/admin/reset_password.php?token=" . $token;

        // Envoi de l'email avec PHPMailer
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = $SMTP_HOST;
            $mail->SMTPAuth   = true;
            $mail->Username   = $SMTP_USER;
            $mail->Password   = $SMTP_PASS;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = $SMTP_PORT;

            $mail->setFrom($SMTP_USER, 'Projet Blog');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = "🔑 Réinitialisation de votre mot de passe";
            $mail->Body    = "<p>Bonjour,</p>
                              <p>Pour réinitialiser votre mot de passe, cliquez sur le lien ci-dessous :</p>
                              <p><a href='$reset_link'>$reset_link</a></p>
                              <p>Ce lien est valide pendant <strong>24 heures</strong>.</p>";

            $mail->send();
            $message = "✅ Un email de réinitialisation a été envoyé à votre adresse.";
        } catch (Exception $e) {
            $message = "❌ Erreur lors de l'envoi de l'email : " . $mail->ErrorInfo;
        }
    } else {
        $message = "⚠️ Aucun compte trouvé avec cet email.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Mot de passe oublié</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="login.php">🔑 Connexion</a>
        </div>
    </nav>

    <!-- Contenu principal -->
    <div class="container d-flex justify-content-center align-items-center" style="height: 90vh;">
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-body">
                    <h3 class="card-title text-center mb-4">🔁 Réinitialisation du mot de passe</h3>

                    <?php if ($message): ?>
                        <div class="alert alert-info text-center"><?= htmlspecialchars($message) ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Adresse email</label>
                            <input type="email" name="email" class="form-control" required placeholder="Entrez votre email">
                        </div>

                        <button type="submit" class="btn btn-primary w-100">📩 Envoyer un lien</button>
                    </form>

                    <div class="text-center mt-3">
                        <a href="login.php" class="text-decoration-none">🔙 Retour à la connexion</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pied de page -->
    <footer class="bg-dark text-white text-center py-3">
        &copy; <?= date('Y') ?> Mon Blog | Tous droits réservés
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>