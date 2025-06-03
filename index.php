<?php
session_start();
$isLoggedIn = isset($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title><?= $isLoggedIn ? "Modifier Profil" : "Inscription" ?></title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
  <h1><?= $isLoggedIn ? "Modifier mes informations" : "Créer un compte" ?></h1>

  <form id="userForm">
    <!-- Tous les champs comme avant -->
    <input type="text" name="nom_complet" placeholder="Nom complet" required>
    <input type="text" name="telephone" placeholder="Téléphone" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="date" name="date_naissance" required>
    <select name="genre">
      <option value="masculin">Masculin</option>
      <option value="feminin">Féminin</option>
    </select>
    <textarea name="biographie" placeholder="Biographie"></textarea>

    <?php if (!$isLoggedIn): ?>
      <input type="text" name="login" placeholder="Identifiant" required>
      <input type="password" name="mot_de_passe" placeholder="Mot de passe" required>
    <?php endif; ?>

    <label><input type="checkbox" name="accord"> J'accepte les conditions</label>

    <button type="submit">Envoyer</button>
  </form>

  <div id="resultat"></div>

  <script src="assets/app.js"></script>
</body>
</html>
