<?php
session_start();

$erreur = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $login = $_POST['login'] ?? '';
    $mot_de_passe = $_POST['mot_de_passe'] ?? '';

    try {
        $pdo = new PDO("mysql:host=localhost;dbname=u68658;charset=utf8", "u68658", "7975806");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Erreur de connexion : " . $e->getMessage());
    }

    $stmt = $pdo->prepare("SELECT * FROM users WHERE login = ?");
    $stmt->execute([$login]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($mot_de_passe, $user['mot_de_passe_hash'])) {
        $_SESSION['user_id'] = $user['id'];
        header("Location: profil.php");
        exit();
    } else {
        $erreur = "Login ou mot de passe incorrect.";
    }
}
?>

<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Connexion</title></head>
<body>
    <h2>Connexion</h2>
    <?php if ($erreur): ?><p style="color:red;"><?= htmlspecialchars($erreur) ?></p><?php endif; ?>
    <form method="POST">
        <label>Login : <input type="text" name="login" required></label><br>
        <label>Mot de passe : <input type="password" name="mot_de_passe" required></label><br>
        <button type="submit">Se connecter</button>
    </form>
</body>
</html>
