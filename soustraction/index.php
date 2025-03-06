<?php
@ob_start(); // Démarre la mise en mémoire tampon de la sortie (utile pour les redirections)
include 'utils.php'; // Inclut un fichier qui contient des fonctions utilitaires (par exemple, pour loguer des informations)
log_adresse_ip("logs/log.txt", "index.php"); // Appelle une fonction pour enregistrer l'adresse IP dans un fichier log

session_start(); // Démarre la session pour pouvoir utiliser les variables de session
// Initialisation des variables de session
$_SESSION['nbMaxQuestions'] = 10; // Nombre maximal de questions à poser
$_SESSION['nbQuestion'] = 0; // Initialisation du nombre de questions répondues
$_SESSION['nbBonneReponse'] = 0; // Initialisation du nombre de bonnes réponses
$_SESSION['prenom'] = ""; // Initialisation du prénom de l'utilisateur
$_SESSION['historique'] = ""; // Initialisation de l'historique des questions/réponses
$_SESSION['origine'] = "index"; // Définition de la page d'origine

include '../Connexion/config.php'; // Inclut un fichier pour la connexion à la base de données

// Vérification si l'utilisateur est connecté, sinon rediriger vers la page de login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Si l'utilisateur n'est pas connecté, on le redirige vers la page de login
    exit(); // On s'assure que le script s'arrête ici
}

// On récupère l'ID de l'utilisateur depuis la session
$user_id = $_SESSION['user_id']; // Utilisation de l'index correct pour obtenir l'ID de l'utilisateur

// Préparation et exécution de la requête SQL pour récupérer le nom d'utilisateur
$sql = "SELECT username FROM users WHERE id = ?"; // Requête pour obtenir le username de l'utilisateur
$stmt = $pdo->prepare($sql); // Préparation de la requête
$stmt->execute([$user_id]); // Exécution de la requête avec le paramètre de l'ID de l'utilisateur
$user = $stmt->fetch(); // Récupération du résultat de la requête

// Vérification si un utilisateur a été trouvé et assignation de son prénom
$prenom = $user ? htmlspecialchars($user['username']) : ''; // Si un utilisateur est trouvé, on nettoie le username, sinon on laisse une chaîne vide
?>

<!doctype html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title>Accueil</title>
	</head>
	<body style="background-color:grey;">
		<?php 
			$_POST['nbQuestion']=0;
			$_POST['nbBonneReponse']=0;
			$_POST['prenom']="";
			$_POST['historique']="";
			$_POST['nbMaxQuestions']=10;
		?> 
		<center>
			<table border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td style="width:1000px;height:430px;background-image:url('./images/NO.jpg');background-repeat:no-repeat;">
						<center>
						
						
						
						
						
							<h1>Bonjour !</h1><br />
							<h2>Nous allons faire du calcul mental. Tu devras faire <?php echo ''.$_SESSION['nbMaxQuestions'].'' ?> calculs.</h2><br />
							<h3>Mais avant, Quel est ton prénom ?</h3>
							<form action="./question.php" method="post">
							<input type="text" id="prenom" name="prenom" autocomplete="off" autofocus value="<?php echo $prenom; ?>" readonly> <!-- Champ de texte avec prénom déjà rempli et en lecture seule -->
								<input type="submit" value="Commencer">
							</form>
						
						
						
						
						
						
						</center>
					</td>
					<td style="width:280px;height:430px;background-image:url('./images/NE.jpg');background-repeat:no-repeat;"></td>
				</tr>
				<tr>
					<td style="width:1000px;height:323px;background-image:url('./images/SO.jpg');background-repeat:no-repeat;"></td>
					<td style="width:280px;height:323px;background-image:url('./images/SE.jpg');background-repeat:no-repeat;"></td>
				</tr>
			</table>
		</center>
		<br />
		<footer style="background-color: #45a1ff;">
			<center>
				Rémi Synave<br />
				Contact : remi . synave @ univ - littoral [.fr]<br />
				Crédits image : Image par <a href="https://pixabay.com/fr/users/Mimzy-19397/">Mimzy</a> de <a href="https://pixabay.com/fr/?utm_source=link-attribution&amp;utm_medium=referral&amp;utm_campaign=image&amp;utm_content=1576791">Pixabay</a> <br />
				et Image par <a href="https://pixabay.com/fr/users/everesd_design-16482457/">everesd_design</a> de <a href="https://pixabay.com/fr/?utm_source=link-attribution&amp;utm_medium=referral&amp;utm_campaign=image&amp;utm_content=5213756">Pixabay</a> <br />
			</center>
		</footer>
	</body>
</html>
