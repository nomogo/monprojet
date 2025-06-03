<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

try {
    $pdo = new PDO("mysql:host=localhost;dbname=u68658;charset=utf8", "u68658", "7975806");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur DB: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Bienvenue <?= htmlspecialchars($user['nom_complet']) ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Bienvenue sur le site, <?= htmlspecialchars($user['nom_complet']) ?> !</h1>
    <p>Voici votre profil :</p>
    <ul>
        <li><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></li>
        <li><strong>Téléphone:</strong> <?= htmlspecialchars($user['telephone']) ?></li>
        <li><strong>Date de naissance:</strong> <?= htmlspecialchars($user['date_naissance']) ?></li>
        <li><strong>Biographie:</strong> <?= nl2br(htmlspecialchars($user['biographie'])) ?></li>
    </ul>
    <p><a href="modifier.php">Modifier mes infos</a> | <a href="logout.php">Déconnexion</a></p>
</body>
</html>
