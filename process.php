// Démarrer session et connecter l'utilisateur
$_SESSION['user_id'] = $utilisateur_id;
$_SESSION['user_login'] = $login;

// Rediriger vers le site principal (ex : dashboard.php)
header("Location: dashboard.php");
exit();
