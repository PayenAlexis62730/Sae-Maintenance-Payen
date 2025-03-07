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
$redirect_user_id = isset($_GET['redirect_id']) ? $_GET['redirect_id'] : null;  // ID de retour (parent ou enseignant)

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
$sql = "SELECT * FROM exercices WHERE student_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$viewed_user_id]);
$exercices = $stmt->fetchAll();

// Récupérer les exercices créés par l'enseignant pour les étudiants
if ($user['role'] == 'enseignant' && $viewed_user['role'] == 'enfant') {
    // Jointure entre exercices et users pour récupérer les exercices créés par l'enseignant pour ses étudiants
    $sql = "SELECT e.* 
            FROM exercices e
            JOIN users u ON u.id = e.student_id
            WHERE u.teacher_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id]);
    $teacher_exercices = $stmt->fetchAll();
}
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
                
                <?php if ($viewed_user['role'] == 'enfant'): ?>
                    <p><strong>Classe :</strong> <?php echo htmlspecialchars($viewed_user['class_level']); ?></p> <!-- Affichage de la classe -->
                <?php endif; ?>
            </div>

            <?php if ($user['role'] == 'enseignant' && $viewed_user_id == $user_id): ?>
                <!-- Section Créer un Exercice -->
                <div class="card">
                    <h2>Créer un Exercice</h2>
                    <p>Vous pouvez créer un exercice pour vos étudiants.</p>
                    <a href="exercice_config.php">
                        <button class="button-home">Créer un Exercice</button>
                    </a>
                </div>
            <?php endif; ?>

            <?php if ($viewed_user['role'] == 'enfant'): ?>
                <!-- Section Voir les Exercices pour les enfants -->
                <?php if ($user['role'] == 'enfant'): ?>
                    <div class="card">
                        <h2>Voir les Exercices</h2>
                        <p>Les exercices créés par votre enseignant :</p>
                        <?php if (!empty($teacher_exercices)): ?>
                            <ul>
                                <?php foreach ($teacher_exercices as $exercice): ?>
                                    <li>
                                        <?php echo htmlspecialchars($exercice['exercice_name']); ?> 
                                        - <?php echo $exercice['date_created']; ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p>Aucun exercice disponible pour vous.</p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                
                <!-- Section exercices réalisés -->
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
                                <a href="profile.php?user_id=<?php echo $child['id']; ?>&redirect_id=<?php echo $user_id; ?>">
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
                        ?>
                            <li>
                                <!-- Lien vers le profil de l'enfant, en passant son id et le redirect_id -->
                                <a href="profile.php?user_id=<?php echo $student_id; ?>&redirect_id=<?php echo $user_id; ?>">
                                    <?php echo htmlspecialchars($student['username']); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <!-- Bouton "Retour au profil" -->
            <?php if ($redirect_user_id): ?>
                <div class="back-to-profile">
                    <a href="profile.php?user_id=<?php echo $redirect_user_id; ?>">
                        <button class="button-back">Retour à mon Profil</button>
                    </a>
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
