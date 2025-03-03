<?php
include 'config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];

    // Vérifier si l'email existe déjà
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        $error = "❌ Cet email est déjà utilisé !";
    } elseif ($password != $password_confirm) {
        $error = "❌ Les mots de passe ne correspondent pas !";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute([$username, $email, $hashed_password])) {
            $_SESSION['username'] = $username;
            $_SESSION['user_id'] = $pdo->lastInsertId();
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
                        <label>Nom d'utilisateur :</label><br>
                        <input type="text" name="username" required><br><br>

                        <label>Email :</label><br>
                        <input type="email" name="email" required><br><br>

                        <label>Mot de passe :</label><br>
                        <input type="password" name="password" required><br><br>

                        <label>Confirmer le mot de passe :</label><br>
                        <input type="password" name="password_confirm" required><br><br>

                        <button type="submit">S'inscrire</button>
                    </form>

                    <!-- Affichage des erreurs -->
                    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

                    <br>
                    <a href="login.php">Déjà un compte ? Connectez-vous</a>
                </td>
            </tr>
        </table>
    </center>
</body>
</html>
