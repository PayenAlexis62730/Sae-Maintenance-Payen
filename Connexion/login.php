<?php
include 'config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $identifier = trim($_POST['identifier']); // Email ou username
    $password = $_POST['password'];

    // Vérifier si l'input correspond à un email ou un username
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? OR username = ?");
    $stmt->execute([$identifier, $identifier]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['username'] = $user['username'];
        $_SESSION['user_id'] = $user['id'];
        header("Location: ../index.php");
        exit();
    } else {
        $error = "❌ Identifiants incorrects !";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
</head>
<body style="background-color: grey;">
    <center>
        <h1>Connexion</h1>
        <table border="1" cellpadding="15" style="border-collapse:collapse; border: 15px solid #ff7700; background-color: #d6d6d6;">
            <tr>
                <td>
                    <form method="POST">
                        <label for="identifier">Email ou Nom d'utilisateur :</label><br>
                        <input type="text" name="identifier" required><br><br>

                        <label for="password">Mot de passe :</label><br>
                        <input type="password" name="password" required><br><br>

                        <button type="submit">Se connecter</button>
                    </form>

                    <!-- Affichage des erreurs -->
                    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

                    <br>
                    <small>Vous n'avez pas encore de compte ? <a href="signup.php">Inscrivez-vous ici</a></small>
                </td>
            </tr>
        </table>
    </center>
</body>
</html>
