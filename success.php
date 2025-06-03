<?php
session_start();
if (!isset($_SESSION['nouvel_utilisateur'])) {
    header("Location: index.php");
    exit();
}

$login = $_SESSION['nouvel_utilisateur']['login'];
$password = $_SESSION['nouvel_utilisateur']['password'];
unset($_SESSION['nouvel_utilisateur']);

$profil_url = "profil.php"; // ou par exemple "profil.php?id=123"
?>

<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Inscription réussie</title></head>
<body>
    <h1>Inscription réussie !</h1>
    <p>Identifiants de connexion à conserver :</p>
    <ul>
        <li><strong>Login :</strong> <?= htmlspecialchars($login) ?></li>
        <li><strong>Mot de passe :</strong> <?= htmlspecialchars($password) ?></li>
    </ul>
    <p>Accédez à votre <a href="<?= $profil_url ?>">profil ici</a>.</p>
</body>
</html>
