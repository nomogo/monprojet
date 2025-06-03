<?php
// Pour repopuler les champs si erreurs
$form_values = isset($_COOKIE['form_values']) ? json_decode($_COOKIE['form_values'], true) : [];
$erreurs = isset($_COOKIE['form_errors']) ? json_decode($_COOKIE['form_errors'], true) : [];
$langages_autorises = ["Pascal", "C", "C++", "JavaScript", "PHP", "Python", "Java", "Haskel", "Clojure", "Prolog", "Scala", "Go"];
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Créer un compte</h1>

    <?php
    if ($erreurs) {
        echo "<div class='error'><ul>";
        foreach ($erreurs as $err) {
            echo "<li>" . htmlspecialchars($err) . "</li>";
        }
        echo "</ul></div>";
    }
    ?>

    <form method="POST" action="process.php">
        <label>Nom complet :
            <input type="text" name="nom_complet" value="<?= htmlspecialchars($form_values['nom_complet'] ?? '') ?>" required>
        </label><br>

        <label>Téléphone :
            <input type="text" name="telephone" value="<?= htmlspecialchars($form_values['telephone'] ?? '') ?>" required>
        </label><br>

        <label>Email :
            <input type="email" name="email" value="<?= htmlspecialchars($form_values['email'] ?? '') ?>" required>
        </label><br>

        <label>Date de naissance :
            <input type="date" name="date_naissance" value="<?= htmlspecialchars($form_values['date_naissance'] ?? '') ?>" required>
        </label><br>

        <label>Genre :
            <select name="genre" required>
                <option value="">--Choisir--</option>
                <option value="masculin" <?= ($form_values['genre'] ?? '') == 'masculin' ? 'selected' : '' ?>>Masculin</option>
                <option value="feminin" <?= ($form_values['genre'] ?? '') == 'feminin' ? 'selected' : '' ?>>Féminin</option>
            </select>
        </label><br>

        <label>Biographie :<br>
            <textarea name="biographie"><?= htmlspecialchars($form_values['biographie'] ?? '') ?></textarea>
        </label><br>

        <fieldset>
            <legend>Langages préférés :</legend>
            <?php foreach ($langages_autorises as $langage): ?>
                <label>
                    <input type="checkbox" name="langages[]" value="<?= $langage ?>"
                        <?= (isset($form_values['langages']) && in_array($langage, $form_values['langages'])) ? 'checked' : '' ?>>
                    <?= $langage ?>
                </label><br>
            <?php endforeach; ?>
        </fieldset><br>

        <label><input type="checkbox" name="accord" <?= isset($form_values['accord']) ? 'checked' : '' ?>> J'accepte les conditions</label><br>

        <label>Login :
            <input type="text" name="login" value="<?= htmlspecialchars($form_values['login'] ?? '') ?>" required>
        </label><br>

        <label>Mot de passe :
            <input type="password" name="mot_de_passe" required>
        </label><br>

        <button type="submit">S'inscrire</button>
    </form>
</body>
</html>
