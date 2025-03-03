<?php
// Démarrage de la session
session_start();

// Détruire toutes les variables de session
session_destroy();

// Redirection vers la page d'accueil (index.php) après la déconnexion
header("Location: ../index.php");

// Terminer l'exécution du script pour s'assurer que rien d'autre ne sera exécuté après la redirection
exit();
?>
