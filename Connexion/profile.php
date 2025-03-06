<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

// Vérifier si un parent ou enseignant consulte le profil d'un enfant
$viewed_user_id = isset($_GET['user_id']) ? $_GET['user_id'] : $user_id;

// Récupérer les informations de l'utilisateur actuel
$sql = "SELECT role FROM users WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Récupérer les informations de l'utilisateur dont on consulte le profil
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$viewed_user_id]);
$viewed_user = $stmt->fetch();

// Récupérer les exercices de l'utilisateur affiché
$sql = "SELECT * FROM exercices WHERE user_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$viewed_user_id]);
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
                <p>Profil de <?php echo htmlspecialchars($viewed_user['username']); ?></p>
            </div>
        </header>

        <!-- Profil Content -->
        <section class="profile-content">
            <div class="card">
                <h2>Informations</h2>
                <p><strong>Nom d'utilisateur :</strong> <?php echo htmlspecialchars($viewed_user['username']); ?></p>
                <p><strong>Rôle :</strong> <?php echo htmlspecialchars($viewed_user['role']); ?></p>
            </div>

            <?php if ($viewed_user['role'] == 'enfant'): ?>
                <!-- Section exercices pour les enfants -->
                <div class="card">
                    <h2>Exercices Réalisés</h2>
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

            <?php elseif ($user['role'] == 'parent'): ?>
                <!-- Section enfants pour les parents -->
                <div class="card">
                    <h2>Vos Enfants</h2>
                    <ul>
                        <?php
                        $sql = "SELECT id, username FROM users WHERE role = 'enfant' AND parent_id = ?";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute([$user_id]);
                        $children = $stmt->fetchAll();

                        foreach ($children as $child): ?>
                            <li>
                                <a href="profile.php?user_id=<?php echo $child['id']; ?>">
                                    <?php echo htmlspecialchars($child['username']); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

            <?php elseif ($user['role'] == 'enseignant'): ?>

                <!-- Section étudiants pour les enseignants -->
                <div class="card">
                    <h2>Vos Étudiants</h2>
                    <ul>
                        <?php
                        // Récupérer les enfants associés à l'enseignant
                        $sql = "SELECT id, username FROM users WHERE role = 'enfant' AND teacher_id = ?";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute([$user_id]);
                        $students = $stmt->fetchAll();

                        foreach ($students as $student):
                            // On récupère l'id de l'enfant à partir de son username
                            $student_id = $student['id'];
                            //echo $student_id; 
                        ?>
                            <li>
                                <!-- Lien vers le profil de l'enfant, en passant son id -->
                                <a href="profile.php?user_id=<?php echo $student_id; ?>">
                                    <?php echo htmlspecialchars($student['username']); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
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
