<?php
// Inclusion du fichier de configuration pour la connexion à la base de données
include 'config.php';

// Démarrage de la session afin d'utiliser des variables de session pour stocker des informations de l'utilisateur
session_start();

// Vérification que la méthode de la requête est POST, ce qui signifie que le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupération de l'identifiant (qui peut être un email ou un nom d'utilisateur) et du mot de passe depuis le formulaire
    $identifier = trim($_POST['identifier']); // Trim pour enlever les espaces superflus avant et après
    $password = $_POST['password'];  // Mot de passe

    // Préparation d'une requête SQL pour rechercher un utilisateur avec l'email ou le nom d'utilisateur fourni
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? OR username = ?");
    $stmt->execute([$identifier, $identifier]);
    $user = $stmt->fetch(); // Récupérer l'utilisateur trouvé, s'il existe

    // Vérification si l'utilisateur existe et si le mot de passe correspond à celui enregistré en base
    if ($user && password_verify($password, $user['password'])) {
        // Si l'utilisateur est trouvé et le mot de passe est correct, on crée les variables de session
        $_SESSION['username'] = $user['username']; // Stockage du nom d'utilisateur dans la session
        $_SESSION['user_id'] = $user['id']; // Stockage de l'id de l'utilisateur dans la session
        
        // Redirection vers la page d'accueil après une connexion réussie
        header("Location: ../index.php");
        exit();
    } else {
        // Si l'utilisateur n'est pas trouvé ou si le mot de passe est incorrect, on affiche un message d'erreur
        $error = "❌ Identifiants incorrects !";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <!-- Définition de l'encodage du caractère et du titre de la page -->
    <meta charset="UTF-8">
    <title>Connexion</title>
</head>
<body style="background-color: grey;">
    <center>
        <h1>Connexion</h1>
        <!-- Création d'un tableau pour afficher le formulaire -->
        <table border="1" cellpadding="15" style="border-collapse:collapse; border: 15px solid #ff7700; background-color: #d6d6d6;">
            <tr>
                <td>
                    <!-- Formulaire de connexion -->
                    <form method="POST">
                        <!-- Champ pour l'email ou le nom d'utilisateur -->
                        <label for="identifier">Email ou Nom d'utilisateur :</label><br>
                        <input type="text" name="identifier" required><br><br>

                        <!-- Champ pour le mot de passe -->
                        <label for="password">Mot de passe :</label><br>
                        <input type="password" name="password" required><br><br>

                        <!-- Bouton pour soumettre le formulaire -->
                        <button type="submit">Se connecter</button>
                    </form>

                    <!-- Affichage d'un message d'erreur s'il y en a un -->
                    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

                    <br>
                    <!-- Lien pour s'inscrire si l'utilisateur n'a pas de compte -->
                    <small>Vous n'avez pas encore de compte ? <a href="signup.php">Inscrivez-vous ici</a></small>
                </td>
            </tr>
        </table>
    </center>
</body>
</html>
