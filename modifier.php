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

if (!$user) die("Utilisateur introuvable.");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = htmlspecialchars(trim($_POST["nom_complet"]));
    $telephone = htmlspecialchars(trim($_POST["telephone"]));
    $email = htmlspecialchars(trim($_POST["email"]));
    $biographie = htmlspecialchars(trim($_POST["biographie"]));
    $langages = isset($_POST["langages"]) ? json_encode($_POST["langages"]) : '[]';

    $stmt = $pdo->prepare("UPDATE users SET nom_complet = ?, telephone = ?, email = ?, biographie = ?, langages = ? WHERE id = ?");
    $stmt->execute([$nom, $telephone, $email, $biographie, $langages, $id]);

    echo "<p style='color:green;'>Mise à jour réussie !</p>";
}
?>

<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Modifier Profil</title></head>
<body>
    <h2>Modifier vos informations</h2>
    <form method="POST">
        <label>Nom complet : <input type="text" name="nom_complet" value="<?= htmlspecialchars($user['nom_complet']) ?>"></label><br>
        <label>Téléphone : <input type="text" name="telephone" value="<?= htmlspecialchars($user['telephone']) ?>"></label><br>
        <label>Email : <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>"></label><br>
        <label>Biographie : <textarea name="biographie"><?= htmlspecialchars($user['biographie']) ?></textarea></label><br>
        <label>Langages préférés :</label><br>
        <?php
            $selected = json_decode($user['langages'], true) ?? [];
            $tous = ["PHP", "Python", "Java", "JavaScript", "Go", "C++"];
            foreach ($tous as $lang) {
                $checked = in_array($lang, $selected) ? "checked" : "";
                echo "<label><input type='checkbox' name='langages[]' value='$lang' $checked> $lang</label><br>";
            }
        ?>
        <button type="submit">Mettre à jour</button>
    </form>
</body>
</html>
