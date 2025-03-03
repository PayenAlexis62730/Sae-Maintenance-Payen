<?php
@ob_start(); // Démarre la mise en mémoire tampon de la sortie (utile pour les redirections et éviter les erreurs d'entêtes déjà envoyés)

// Inclusion du fichier 'utils.php' contenant des fonctions utilitaires
include 'utils.php';

// Enregistrement de l'adresse IP dans un fichier log pour la page 'raz.php'
log_adresse_ip("logs/log.txt", "raz.php");

// Détruit toutes les variables de session et ferme la session
session_destroy(); // Détruit la session en cours (les données de la session sont supprimées)
session_unset();   // Libère toutes les variables de session
unset($_POST);     // Libère toutes les données envoyées via le formulaire ($_POST) pour éviter toute fuite de données

// Redirige l'utilisateur vers la page d'accueil après la déconnexion
header('Location: ./index.php');
exit(); // On s'assure que le script s'arrête ici après la redirection
?>
