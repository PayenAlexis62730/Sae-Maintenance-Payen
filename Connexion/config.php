<?php
// Paramètres de connexion à la base de données
$host = 'localhost:3308'; // Le nom d'hôte (localhost) et le port (3308). Si votre MySQL fonctionne sur le port par défaut (3306), vous pouvez simplement écrire 'localhost'.
$dbname = 'exercice_app'; // Le nom de la base de données à laquelle vous voulez vous connecter
$username = 'root'; // Le nom d'utilisateur pour accéder à la base de données (ici 'root' pour un serveur local)
$password = ''; // Le mot de passe pour l'utilisateur MySQL. S'il n'y en a pas, laissez la chaîne vide.

// Tentative de connexion à la base de données avec PDO
try {
    // Création de l'instance PDO pour se connecter à la base de données
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

    // Définir le mode d'erreur de PDO pour qu'il lance une exception en cas d'erreur
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Si la connexion réussit, vous pouvez ajouter du code ici pour interagir avec la base de données
    echo "Connexion réussie à la base de données $dbname!";
} catch (PDOException $e) {
    // Si la connexion échoue, une exception est lancée et l'erreur est affichée
    die("Erreur de connexion : " . $e->getMessage());
}
?>
