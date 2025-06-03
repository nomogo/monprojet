<?php
session_start();

function sanitize($data) {
    return htmlspecialchars(trim($data));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom_complet = sanitize($_POST["nom_complet"]);
    $telephone = sanitize($_POST["telephone"]);
    $email = sanitize($_POST["email"]);
    $date_naissance = $_POST["date_naissance"];
    $genre = $_POST["genre"];
    $biographie = sanitize($_POST["biographie"]);
    $accord = isset($_POST["accord"]) ? 1 : 0;
    $langages = $_POST["langages"] ?? [];
    $login = sanitize($_POST["login"]);
    $mot_de_passe = $_POST["mot_de_passe"];

    $erreurs = [];
    $langages_disponibles = ["Pascal", "C", "C++", "JavaScript", "PHP", "Python", "Java", "Haskel", "Clojure", "Prolog", "Scala", "Go"];

    if (!preg_match("/^[a-zA-Zà-ÿ\s\-]+$/u", $nom_complet) || strlen($nom_complet) > 150) {
        $erreurs[] = "Le nom complet est invalide.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erreurs[] = "Email invalide.";
    }

    if (!preg_match("/^[0-9\s\-\+]+$/", $telephone)) {
        $erreurs[] = "Numéro de téléphone invalide.";
    }

    if (!in_array($genre, ["masculin", "feminin"])) {
        $erreurs[] = "Genre invalide.";
    }

    foreach ($langages as $lang) {
        if (!in_array($lang, $langages_disponibles)) {
            $erreurs[] = "Langage non autorisé : $lang";
        }
    }

    if (strlen($mot_de_passe) < 6) {
        $erreurs[] = "Mot de passe trop court.";
    }

    if ($accord !== 1) {
        $erreurs[] = "Veuillez accepter les conditions.";
    }

    if (!preg_match("/^[a-zA-Z0-9_]{4,20}$/", $login)) {
        $erreurs[] = "Login invalide.";
    }

    if ($erreurs) {
        setcookie("form_errors", json_encode($erreurs), 0, "/");
        setcookie("form_values", json_encode($_POST), 0, "/");
        header("Location: index.php");
        exit();
    }

    try {
        $pdo = new PDO("mysql:host=localhost;dbname=u68658;charset=utf8", "u68658", "7975806");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Vérifie si le login existe déjà
        $check = $pdo->prepare("SELECT id FROM users WHERE login = ?");
        $check->execute([$login]);
        if ($check->fetch()) {
            setcookie("form_errors", json_encode(["Ce login est déjà pris."]), 0, "/");
            setcookie("form_values", json_encode($_POST), 0, "/");
            header("Location: index.php");
            exit();
        }

        $hash = password_hash($mot_de_passe, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("INSERT INTO users (nom_complet, telephone, email, date_naissance, genre, biographie, accord, login, mot_de_passe_hash)
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$nom_complet, $telephone, $email, $date_naissance, $genre, $biographie, $accord, $login, $hash]);
        $utilisateur_id = $pdo->lastInsertId();

        // Enregistre les langages préférés
        $stmt2 = $pdo->prepare("INSERT INTO user_languages (utilisateur_id, langage) VALUES (?, ?)");
        foreach ($langages as $lang) {
            $stmt2->execute([$utilisateur_id, $lang]);
        }

        $_SESSION['user_id'] = $utilisateur_id;
        $_SESSION['user_login'] = $login;

        header("Location: dashboard.php");
        exit();

    } catch (PDOException $e) {
        die("Erreur DB : " . $e->getMessage());
    }
}
?>
