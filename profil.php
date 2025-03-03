<?php
session_start();
include('config.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirection vers la page de connexion
    exit();
}

$user_id = $_SESSION['user_id'];

// Récupérer les statistiques des exercices
$sql = "SELECT exercise_name, score, date FROM exercises WHERE user_id = ? ORDER BY date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<h1>Profil de <?php echo $_SESSION['username']; ?></h1>

<h2>Statistiques des exercices</h2>
<table>
    <tr>
        <th>Exercice</th>
        <th>Score</th>
        <th>Date</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()) { ?>
    <tr>
        <td><?php echo $row['exercise_name']; ?></td>
        <td><?php echo $row['score']; ?></td>
        <td><?php echo $row['date']; ?></td>
    </tr>
    <?php } ?>
</table>
