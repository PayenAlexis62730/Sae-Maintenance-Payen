<?php
// Inclusion du fichier de configuration pour la connexion à la base de données
include 'config.php';

// Démarrage de la session pour récupérer les informations de l'utilisateur connecté
session_start();

// Vérifier si l'utilisateur est connecté en vérifiant l'existence de la variable 'user_id' dans la session
if (!isset($_SESSION['user_id'])) {
    // Si l'utilisateur n'est pas connecté, rediriger vers la page de connexion
    header("Location: login.php");
    exit();
}

// Récupérer l'ID de l'utilisateur à partir de la session
$user_id = $_SESSION['user_id'];

// Récupérer les données envoyées par le formulaire (le nom de l'exercice et le score)
$exercice_name = $_POST['exercice_name'];
$score = $_POST['score'];

// Préparer la requête SQL pour insérer les données dans la table 'exercices'
$sql = "INSERT INTO exercices (user_id, exercice_name, score) VALUES (?, ?, ?)";
$stmt = $pdo->prepare($sql);

// Exécuter la requête SQL en liant les valeurs des variables
$stmt->execute([$user_id, $exercice_name, $score]);

// Afficher un message indiquant que l'exercice a bien été enregistré
echo "Exercice enregistré!";
?>
