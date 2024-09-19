<?php
session_start();
require_once 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $identifiant = $_POST["connexionidentifiant"];
    $mdp = $_POST["connexionMdp"];

    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$identifiant]);
    $user = $stmt->fetch();

    if ($user && password_verify($mdp, $user["password"])) {
        $_SESSION["user_id"] = $user["id"];
        header("Location: ../user/dashboard.php");
        exit();
    } else {
        echo "Identifiant ou mot de passe incorrect.";
    }
}
?>