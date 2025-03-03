<?php
$host = 'localhost:3308'; // retirer :3308 si votre part de base est 3306
$dbname = 'exercice_app';
$username = 'root'; // mettez votre utilisateur MySQL
$password = ''; // mettez votre mot de passe MySQL

// Connexion à la base de données
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>
