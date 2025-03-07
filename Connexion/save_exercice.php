<?php
include 'config.php'; // Connexion à la base de données

// Vérifie si les variables de session nécessaires sont définies
if (isset($_SESSION['user_id'], $_SESSION['nbBonneReponse'], $_SESSION['exercice_name'])) {
    $student_id = $_SESSION['user_id']; // Récupère l'ID de l'étudiant
    $score = $_SESSION['nbBonneReponse']; // Récupère le score obtenu
    $exercice_name = $_SESSION['exercice_name']; // Récupère le nom de l'exercice
    $date_completed = date('Y-m-d H:i:s'); // Enregistre la date et l'heure actuelles

    // Insère les résultats de l'exercice dans la base de données
    $sql = "INSERT INTO exercices (student_id, exercice_name, score, date_completed) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$student_id, $exercice_name, $score, $date_completed]); // Exécute la requête avec les valeurs
}
?>
