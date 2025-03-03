<?php
$servername = "localhost:3308";
$username = "root"; // à remplacer par votre nom d'utilisateur MySQL
$password = ""; // à remplacer par votre mot de passe MySQL
$dbname = "sae_maintenance";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}
?>
