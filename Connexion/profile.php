<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

// Récupérer les exercices réalisés par l'utilisateur
$sql = "SELECT * FROM exercices WHERE user_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$exercices = $stmt->fetchAll();
?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Profil</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="profile-page">
        <!-- Header section -->
        <header class="profile-header">
            <div class="user-info">
                <h1>Bienvenue, <?php echo htmlspecialchars($username); ?></h1>
                <p>Voici votre profil et vos exercices réalisés !</p>
            </div>
        </header>

        <!-- Profil Content -->
        <section class="profile-content">
            <div class="card">
                <h2>Vos Exercices Réalisés</h2>
                <table>
                    <tr>
                        <th>Exercice</th>
                        <th>Score</th>
                        <th>Date</th>
                    </tr>
                    <?php foreach ($exercices as $exercice): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($exercice['exercice_name']); ?></td>
                            <td><?php echo $exercice['score']; ?></td>
                            <td><?php echo $exercice['date_completed']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>

            <div class="card">
                <h2>Statistiques</h2>
                <p><strong>Nombre total d'exercices réalisés:</strong> <?php echo count($exercices); ?></p>
                <p><strong>Score moyen:</strong> 
                <?php
                    $total_score = 0;
                    foreach ($exercices as $exercice) {
                        $total_score += $exercice['score'];
                    }
                    echo count($exercices) > 0 ? round($total_score / count($exercices), 2) : 0;
                ?>
                </p>
            </div>
        </section>

        <!-- Button to go to the homepage -->
        <div class="back-to-home">
            <a href="../index.php"><button class="button-home">Retour à l'Accueil</button></a>
        </div>

        <!-- Footer -->
        <footer class="footer">
            <p>Rémi Synave | Contact: remi.synave@univ-littoral.fr</p>
        </footer>
    </div>
</body>
</html>
