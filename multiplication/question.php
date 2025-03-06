<?php
    @ob_start();
    include 'utils.php';


    session_start();
    $_SESSION['origine']="question";
    if($_SESSION['prenom']=="" && $_POST['prenom']==""){
        log_adresse_ip("logs/log.txt","question.php - accès irrégulier");
        unset($_SESSION);
        unset($_POST);
        header('Location: ./index.php');
    }
    if($_SESSION['prenom']==""){
        $_SESSION['prenom']=$_POST['prenom'];
    }
    $numQuestion=$_SESSION['nbQuestion']+1;

    log_adresse_ip("logs/logs.json", "question.php", ["question_numero" => $numQuestion]);

?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Question</title>
</head>
<body style="background-color:grey;">
<?php 
    // Incrémentation du nombre de questions dans la session
    $_SESSION['nbQuestion'] = $_SESSION['nbQuestion'] + 1;

    // Initialisation des variables pour le calcul
    $nbGauche = 0;
    $nbDroite = 0;
    $operation = 0;
    $reponse = 0;

    // Génération d'un nombre aléatoire pour les deux opérandes
    $nbGauche = mt_rand(100, 10000); // Nombre gauche entre 100 et 10000
    $nbDroite = mt_rand(11, 99); // Nombre droit entre 11 et 99

    // Calcul de l'opération à effectuer
    $operation = $nbGauche . ' x ' . $nbDroite;
    $reponse = $nbGauche * $nbDroite; // Calcul de la réponse correcte
?>
<center>
    <table border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td style="width:1000px;height:430px;background-image:url('./images/NO.jpg');background-repeat:no-repeat;">
                <center>
                    <h1>Question Numéro <?php echo "" . $_SESSION['nbQuestion'] . ""; ?></h1><br />
                    <h3>Combien fait le calcul suivant ?</h3>
                    <h3><?php echo '' . $operation . ' = ?'; ?></h3>
                    <!-- Formulaire pour soumettre la réponse -->
                    <form action="./correction.php" method="post">
                        <!-- Champs cachés pour envoyer l'opération et la réponse correcte au script de correction -->
                        <input type="hidden" name="operation" value="<?php echo '' . $operation . ' = '; ?>"></input>
                        <input type="hidden" name="correction" value="<?php echo '' . $reponse . ''; ?>"></input>
                        <br />
                        <!-- Champ de saisie pour que l'utilisateur entre sa réponse -->
                        <label for="fname">Combien fait le calcul ci-dessus ? </label><br>
                        <input type="text" id="mot" name="mot" autocomplete="off" autofocus><br /><br /><br />
                        <input type="submit" value="Valider">
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
