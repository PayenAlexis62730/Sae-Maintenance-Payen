<?php
include 'config.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$exercice_name = $_POST['exercice_name'];
$score = $_POST['score'];

// Enregistrer l'exercice dans la base de données
$sql = "INSERT INTO exercices (user_id, exercice_name, score) VALUES (?, ?, ?)";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id, $exercice_name, $score]);

echo "Exercice enregistré!";
?>
