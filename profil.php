<?php
session_start();
require 'config.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: connexion.php");
    exit();
}

$user_id = $_SESSION["user_id"];
$username = $_SESSION["username"];

// Récupération des statistiques
$stmt = $conn->prepare("SELECT COUNT(*) AS total_exercises, SUM(score) AS total_score FROM exercises WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($total_exercises, $total_score);
$stmt->fetch();
?>

<h1>Bienvenue, <?php echo $username; ?> !</h1>
<p>Nombre d'exercices réalisés : <?php echo $total_exercises; ?></p>
<p>Score total : <?php echo $total_score; ?></p>
<a href="deconnexion.php">Se déconnecter</a>
