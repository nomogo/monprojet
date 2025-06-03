<?php
session_start();
header("Content-Type: application/json");

// Connexion
$pdo = new PDO("mysql:host=localhost;dbname=u68658;charset=utf8", "u68658", "7975806");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Lecture JSON ou XML
$input = file_get_contents("php://input");

if (stripos($_SERVER["CONTENT_TYPE"], "xml") !== false) {
    $xml = simplexml_load_string($input);
    $data = json_decode(json_encode($xml), true);
} else {
    $data = json_decode($input, true);
}

function sanitize($val) {
    return htmlspecialchars(trim($val));
}

// Identifiant si connecté
$userId = $_SESSION['user_id'] ?? null;

if ($userId) {
    // Mise à jour (sauf login/mdp)
    $stmt = $pdo->prepare("UPDATE users SET nom_complet = ?, telephone = ?, email = ?, date_naissance = ?, genre = ?, biographie = ?, accord = ? WHERE id = ?");
    $stmt->execute([
        sanitize($data['nom_complet']),
        sanitize($data['telephone']),
        sanitize($data['email']),
        sanitize($data['date_naissance']),
        sanitize($data['genre']),
        sanitize($data['biographie']),
        isset($data['accord']) ? 1 : 0,
        $userId
    ]);

    echo json_encode(["status" => "ok", "message" => "Données mises à jour."]);
} else {
    // Création utilisateur
    $login = sanitize($data['login']);
    $stmt = $pdo->prepare("SELECT id FROM users WHERE login = ?");
    $stmt->execute([$login]);
    if ($stmt->rowCount() > 0) {
        http_response_code(409);
        echo json_encode(["error" => "Login déjà utilisé."]);
        exit();
    }

    $hash = password_hash($data["mot_de_passe"], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (nom_complet, telephone, email, date_naissance, genre, biographie, accord, login, mot_de_passe_hash)
                           VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        sanitize($data['nom_complet']),
        sanitize($data['telephone']),
        sanitize($data['email']),
        sanitize($data['date_naissance']),
        sanitize($data['genre']),
        sanitize($data['biographie']),
        isset($data['accord']) ? 1 : 0,
        $login,
        $hash
    ]);

    $id = $pdo->lastInsertId();
    echo json_encode([
        "login" => $login,
        "mot_de_passe" => $data['mot_de_passe'],
        "profile_url" => "https://votre-site.com/profile.php?id=" . $id
    ]);
}
