<?php
session_start();

$erreurs = isset($_COOKIE['form_errors']) ? json_decode($_COOKIE['form_errors'], true) : [];
$valeurs = isset($_COOKIE['form_values']) ? json_decode($_COOKIE['form_values'], true) : [];

setcookie("form_errors", "", time() - 3600, "/");
setcookie("form_values", "", time() - 3600, "/");

$langages_disponibles = ["Pascal", "C", "C++", "JavaScript", "PHP", "Python", "Java", "Haskel", "Clojure", "Prolog", "Scala", "Go"];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h1>Inscription</h1>

<?php if ($erreurs): ?>
    <div class="erreurs">
        <ul>
            <?php foreach ($erreurs as $err): ?>
                <li><?= htmlspecialchars($err) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="POST" action="process.php">
    <label>Nom complet:
        <input type="text" name="nom_complet" value="<?= htmlspecialchars($valeurs['nom_complet'] ?? '') ?>" required>
    </label><br>

    <label>Téléphone:
        <input type="text" name="telephone" value="<?= htmlspecialchars($valeurs['telephone'] ?? '') ?>" required>
    </label><br>

    <label>Email:
        <input type="email" name="email" value="<?= htmlspecialchars($valeurs['email'] ?? '') ?>" required>
    </label><br>

    <label>Date de naissance:
        <input type="date" name="date_naissance" value="<?= htmlspecialchars($valeurs['date_naissance'] ?? '') ?>" required>
    </label><br>

    <label>Genre:
        <select name="genre" required>
            <option value="masculin" <?= ($valeurs['genre'] ?? '') === 'masculin' ? 'selected' : '' ?>>Masculin</option>
            <option value="feminin" <?= ($valeurs['genre'] ?? '') === 'feminin' ? 'selected' : '' ?>>Féminin</option>
        </select>
    </label><br>

    <label>Biographie:
        <textarea name="biographie"><?= htmlspecialchars($valeurs['biographie'] ?? '') ?></textarea>
    </label><br>

    <label>Langages préférés:<br>
        <?php foreach ($langages_disponibles as $lang): ?>
            <input type="checkbox" name="langages[]" value="<?= $lang ?>" 
            <?= in_array($lang, $valeurs['langages'] ?? []) ? 'checked' : '' ?>>
            <?= $lang ?><br>
        <?php endforeach; ?>
    </label><br>

    <label>
        <input type="checkbox" name="accord" required <?= isset($valeurs['accord']) ? 'checked' : '' ?>>
        J'accepte les conditions d'utilisation
    </label><br>

    <label>Login:
        <input type="text" name="login" value="<?= htmlspecialchars($valeurs['login'] ?? '') ?>" required>
    </label><br>

    <label>Mot de passe:
        <input type="password" name="mot_de_passe" required>
    </label><br>

    <button type="submit">S'inscrire</button>
</form>
</body>
</html>
