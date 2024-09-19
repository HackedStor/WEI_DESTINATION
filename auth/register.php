<?php
session_start();
require_once 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $identifiant = $_POST["registeridentifiant"];
    $mdp = $_POST["registerMdp"];

    
    $hashed_password = password_hash($mdp, PASSWORD_DEFAULT);

    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$identifiant]);
    
    if ($stmt->rowCount() > 0) {
        echo "Cet identifiant existe déjà.";
    } else {
        
        $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        if ($stmt->execute([$identifiant, $hashed_password])) {
            header("Location: ../index.php");
            exit();
        } else {
            echo "Erreur lors de l'inscription.";
        }
    }
}
?>