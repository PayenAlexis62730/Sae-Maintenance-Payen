<?php
@ob_start();
include 'utils.php';
include '../Connexion/config.php'; // Connexion à la base de données
session_start();

log_adresse_ip("logs/logs.json", "fin.php", [
    'score_global' => $_SESSION['nbBonneReponse']
]);

$_SESSION['origine'] = "fin";

// Enregistrement des résultats dans la base de données
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $score = $_SESSION['nbBonneReponse'];
    $total_questions = $_SESSION['nbQuestion'];
    $date_completed = date('Y-m-d H:i:s');
    
	$sql = "INSERT INTO exercices (student_id, exercice_name, score, total_questions, date_completed) VALUES (?, 'Soustraction', ?, ?, ?)";
	$stmt = $pdo->prepare($sql);
	$stmt->execute([$user_id, $score, $total_questions, $date_completed]);	
}
?>

<!doctype html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title>Fin de la série</title>
	</head>
	<body style="background-color:grey;">
		<center>
			<table border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td style="width:1000px;height:430px;background-image:url('./images/NO.jpg');background-repeat:no-repeat;">
						<center>
						
							
														
                            <?php
                                if($_SESSION['nbBonneReponse']>1)
                                    echo '<h2>Fin du test.</h2>Tu as '.$_SESSION['nbBonneReponse'].' bonnes réponses sur '.$_SESSION['nbQuestion'].' questions.';
                                else
                                    echo '<h2>Fin du test.</h2>Tu as '.$_SESSION['nbBonneReponse'].' bonne réponse sur '.$_SESSION['nbQuestion'].' questions.';
							$_SESSION['prenom']=strtolower($_SESSION['prenom']);
                            $_SESSION['prenom']=supprime_caracteres_speciaux($_SESSION['prenom']);
							$today = date('Ymd-His'); 
							$fp = fopen('./resultats/'.$_SESSION['prenom'].'-'.$today.'.txt', 'w');
							$_SESSION['historique']=$_SESSION['historique'].''.$_SESSION['nbBonneReponse'];
							fwrite($fp, $_SESSION['historique']);
							fclose($fp);
							
							
							if($_SESSION['nbBonneReponse']>=$_SESSION['nbMaxQuestions']*0.8){
								echo '<h3>Félicitations !</h3>';
								echo '<img src="./images/medailleOr.png" width="100px"><br />';
							}else{								
								if($_SESSION['nbBonneReponse']>=$_SESSION['nbMaxQuestions']*0.6){
									echo '<h3>Très bien !</h3>';
									echo '<img src="./images/medailleArgent.png" width="100px"><br />';
								}else{
									if($_SESSION['nbBonneReponse']>=$_SESSION['nbMaxQuestions']*0.4){
										echo '<h3>Super !</h3>';
										echo '<img src="./images/medailleBronze.png" width="100px"><br />';
									}else{
										echo '<h3>Recommence. Tu peux faire mieux !</h3>';
										echo '<img src="./images/smileyTriste.png" width="100px"><br />';
									}	
								}
							}
							//session_destroy();
							//session_unset();
							?>
							<form action="./index.php" method="post" style="display: inline-block; margin-right: 10px;">
								<input type="submit" value="Recommencer" autofocus>
							</form>

							<form action="../index.php" method="get" style="display: inline-block;">
								<input type="submit" value="Retour à l'accueil">
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
