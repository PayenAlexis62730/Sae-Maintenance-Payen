<?php
include 'config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupération et nettoyage des données
    $username = trim($_POST['username']); // Nom et prénom pour l'enfant, username pour le parent et l'enseignant
    $role = $_POST['role']; // Rôle choisi : enfant, parent ou enseignant
    $email = ($role != 'enfant') ? trim($_POST['email']) : null; // Email pour parent et enseignant
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];
    $child_name = ($role == 'parent') ? $_POST['child_name'] : null; // Enfants pour le parent
    $teacher_students = ($role == 'enseignant') ? $_POST['teacher_students'] : null; // Elèves pour l'enseignant
    $class_level = ($role == 'enfant') ? $_POST['class'] : null; // Classe de l'élève, uniquement pour le rôle "enfant"

    // Si le rôle est parent ou enseignant, on vérifie si l'email existe déjà
    if ($role != 'enfant') {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = "❌ Cet email est déjà utilisé !";
        }
    }

    // Vérification des mots de passe (toujours)
    if ($password != $password_confirm) {
        $error = "❌ Les mots de passe ne correspondent pas !";
    }

    // Vérification de l'existence des enfants pour les parents
    if ($role == 'parent') {
        // Séparer les noms des enfants en un tableau
        $children = explode(',', $child_name);
        $children = array_map('trim', $children); // Enlever les espaces autour des noms
        foreach ($children as $child) {
            $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? AND role = 'enfant'");
            $stmt->execute([$child]);
            if (!$stmt->fetch()) {
                $error = "❌ L'enfant '$child' n'existe pas !";
                break; // Sortir de la boucle dès qu'on trouve un enfant qui n'existe pas
            }
        }
    }

    // Vérification de l'existence des élèves pour les enseignants
    if ($role == 'enseignant') {
        // Séparer les noms des élèves en un tableau
        $students = explode(',', $teacher_students);
        $students = array_map('trim', $students); // Enlever les espaces autour des noms
        foreach ($students as $student) {
            // Vérifier si l'élève existe et s'il est déjà associé à un enseignant
            $stmt = $pdo->prepare("SELECT id, teacher_id FROM users WHERE username = ? AND role = 'enfant'");
            $stmt->execute([$student]);
            $student_data = $stmt->fetch();

            if (!$student_data) {
                $error = "❌ L'élève '$student' n'existe pas !";
                break; // Sortir de la boucle dès qu'on trouve un élève qui n'existe pas
            } elseif ($student_data['teacher_id'] != null) {
                // Si l'élève a déjà un enseignant
                $error = "❌ L'élève '$student' est déjà associé à un autre enseignant.";
                break; // Sortir de la boucle dès qu'on trouve un élève qui a déjà un enseignant
            }
        }
    }

    // Si aucune erreur, on procède à l'insertion dans la base
    if (!isset($error)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (username, email, password, role, child_name, teacher_students, class_level)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute([$username, $email, $hashed_password, $role, $child_name, $teacher_students, $class_level])) {
            $user_id = $pdo->lastInsertId();

            // Si l'utilisateur est un parent, associer l'ID du parent aux enfants
            if ($role == 'parent') {
                // Séparer les noms des enfants en un tableau
                $children = explode(',', $child_name);
                $children = array_map('trim', $children); // Enlever les espaces autour des noms
                foreach ($children as $child) {
                    // Mettre à jour le parent_id de chaque enfant
                    $stmt = $pdo->prepare("UPDATE users SET parent_id = ? WHERE username = ? AND role = 'enfant'");
                    $stmt->execute([$user_id, $child]);
                }
            }

            // Si l'utilisateur est un enseignant, mettre à jour les élèves pour les associer à cet enseignant
            if ($role == 'enseignant') {
                foreach ($students as $student) {
                    // Mettre à jour le teacher_id de chaque élève
                    $stmt = $pdo->prepare("UPDATE users SET teacher_id = ? WHERE username = ? AND role = 'enfant'");
                    $stmt->execute([$user_id, $student]);
                }
            }

            // Créer une session et rediriger vers la page d'accueil
            $_SESSION['username'] = $username;
            $_SESSION['user_id'] = $user_id;
            header("Location: ../index.php");
            exit();
        } else {
            $error = "❌ Erreur lors de l'inscription.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
</head>
<body style="background-color:grey;">
    <center>
        <h1>Inscription</h1>
        <table border="1" cellpadding="15" style="border-collapse:collapse; border: 15px solid #ff7700; background-color:#d6d6d6;">
            <tr>
                <td>
                    <form method="POST">
                        <!-- Pour tous, un champ unique "Nom d'utilisateur" qui sera le nom complet pour un enfant -->
                        <label>Nom d'utilisateur (pour enfant : Prenom Nom) :</label><br>
                        <input type="text" name="username" required><br><br>

                        <!-- Ce champ email est affiché uniquement pour parent et enseignant -->
                        <div id="email_field" style="display:none;">
                            <label>Email :</label><br>
                            <input type="email" name="email"><br><br>
                        </div>

                        <label>Mot de passe :</label><br>
                        <input type="password" name="password" required><br><br>

                        <label>Confirmer le mot de passe :</label><br>
                        <input type="password" name="password_confirm" required><br><br>

                        <!-- Sélection du rôle -->
                        <label>Rôle :</label><br>
                        <select name="role" required id="role_select">
                            <option value="enfant">Enfant</option>
                            <option value="parent">Parent</option>
                            <option value="enseignant">Enseignant</option>
                        </select><br><br>

                        <!-- Pour le rôle parent : entrer le(s) nom(s) d'enfant(s) -->
                        <div id="parent_fields" style="display:none;">
                            <label>Nom des enfants (séparés par une virgule) :</label><br>
                            <input type="text" name="child_name" placeholder="Ex: Enfant1, Enfant2"><br><br>
                        </div>

                        <!-- Pour le rôle enseignant : entrer la liste des élèves -->
                        <div id="enseignant_fields" style="display:none;">
                            <label>Liste des élèves (séparés par une virgule) :</label><br>
                            <input type="text" name="teacher_students" placeholder="Ex: Enfant1, Enfant2, Enfant3"><br><br>
                        </div>

                        <!-- Pour le rôle enfant : entrer la classe -->
                        <div id="enfant_fields" style="display:none;">
                            <label>Classe :</label><br>
                            <select name="class" required>
                                <option value="CP">CP</option>
                                <option value="CE1">CE1</option>
                                <option value="CE2">CE2</option>
                                <option value="CM1">CM1</option>
                                <option value="CM2">CM2</option>
                            </select><br><br>
                        </div>

                        <button type="submit">S'inscrire</button>
                    </form>
                    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
                    <br>
                    <a href="login.php">Déjà un compte ? Connectez-vous</a>
                </td>
            </tr>
        </table>
    </center>

    <script>
        // Script pour afficher/masquer les champs selon le rôle sélectionné
        document.getElementById('role_select').addEventListener('change', function() {
            var role = this.value;
            document.getElementById('email_field').style.display = (role !== 'enfant') ? 'block' : 'none';
            document.getElementById('parent_fields').style.display = (role === 'parent') ? 'block' : 'none';
            document.getElementById('enseignant_fields').style.display = (role === 'enseignant') ? 'block' : 'none';
            document.getElementById('enfant_fields').style.display = (role === 'enfant') ? 'block' : 'none';
        });

        // Déclencher le changement dès le chargement pour afficher le bon champ
        document.getElementById('role_select').dispatchEvent(new Event('change'));
    </script>
</body>
</html>
