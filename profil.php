<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

try {
    $pdo = new PDO("mysql:host=localhost;dbname=u68658;charset=utf8", "u68658", "7975806");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

$id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) die("Profil introuvable.");
?>

<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Mon Profil</title></head>
<body>
    <h2>Bienvenue, <?= htmlspecialchars($user['nom_complet']) ?></h2>
    <ul>
        <li><strong>Email :</strong> <?= htmlspecialchars($user['email']) ?></li>
        <li><strong>Téléphone :</strong> <?= htmlspecialchars($user['telephone']) ?></li>
        <li><strong>Genre :</strong> <?= htmlspecialchars($user['genre']) ?></li>
        <li><strong>Langages préférés :</strong>
            <?= htmlspecialchars(implode(", ", json_decode($user['langages'], true) ?? [])) ?>
        </li>
        <li><strong>Biographie :</strong> <?= nl2br(htmlspecialchars($user['biographie'])) ?></li>
    </ul>

    <p><a href="modifier.php">Modifier mon profil</a></p>
    <p><a href="logout.php">Déconnexion</a></p>
</body>
</html>
