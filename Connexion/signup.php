<?php
// Inclusion du fichier de configuration contenant la connexion à la base de données
include 'config.php';

// Démarrage de la session pour pouvoir gérer les informations de l'utilisateur connecté
session_start();

// Vérifier si le formulaire a été soumis via la méthode POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupération et nettoyage des données envoyées par le formulaire
    $username = trim($_POST['username']); // Nom d'utilisateur
    $email = trim($_POST['email']); // Email
    $password = $_POST['password']; // Mot de passe
    $password_confirm = $_POST['password_confirm']; // Confirmation du mot de passe

    // Vérification si l'email existe déjà dans la base de données
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        // Si l'email existe déjà, afficher une erreur
        $error = "❌ Cet email est déjà utilisé !";
    } elseif ($password != $password_confirm) {
        // Si les mots de passe ne correspondent pas, afficher une erreur
        $error = "❌ Les mots de passe ne correspondent pas !";
    } else {
        // Si aucune erreur, on hache le mot de passe pour plus de sécurité
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Préparer la requête d'insertion des données dans la table 'users'
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        
        // Exécuter la requête d'insertion
        if ($stmt->execute([$username, $email, $hashed_password])) {
            // Si l'insertion réussit, on crée une session pour l'utilisateur
            $_SESSION['username'] = $username;
            $_SESSION['user_id'] = $pdo->lastInsertId(); // Récupérer l'ID de l'utilisateur nouvellement créé
            // Rediriger l'utilisateur vers la page d'accueil
            header("Location: ../index.php");
            exit();
        } else {
            // Si une erreur se produit pendant l'insertion, afficher un message d'erreur
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
        <!-- Formulaire d'inscription -->
        <table border="1" cellpadding="15" style="border-collapse:collapse; border: 15px solid #ff7700; background-color:#d6d6d6;">
            <tr>
                <td>
                    <form method="POST">
                        <!-- Champ pour le nom d'utilisateur -->
                        <label>Nom d'utilisateur :</label><br>
                        <input type="text" name="username" required><br><br>

                        <!-- Champ pour l'email -->
                        <label>Email :</label><br>
                        <input type="email" name="email" required><br><br>

                        <!-- Champ pour le mot de passe -->
                        <label>Mot de passe :</label><br>
                        <input type="password" name="password" required><br><br>

                        <!-- Champ pour confirmer le mot de passe -->
                        <label>Confirmer le mot de passe :</label><br>
                        <input type="password" name="password_confirm" required><br><br>

                        <!-- Bouton pour soumettre le formulaire -->
                        <button type="submit">S'inscrire</button>
                    </form>

                    <!-- Affichage des erreurs s'il y en a -->
                    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

                    <br>
                    <!-- Lien vers la page de connexion si l'utilisateur a déjà un compte -->
                    <a href="login.php">Déjà un compte ? Connectez-vous</a>
                </td>
            </tr>
        </table>
    </center>
</body>
</html>
