<?php
// log adresse ip
// paramètre : nom du fichier de log

function log_adresse_ip($cheminFichierLog, $nomPage, $sessionData = []) {
    date_default_timezone_set('Europe/Paris');

    // Récupération des informations de base
    $logEntry = [
        'timestamp' => (new DateTime())->format('Y-m-d H:i:s'),
        'ip' => $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN',
        'page' => $nomPage,
        'method' => $_SERVER['REQUEST_METHOD'] ?? 'UNKNOWN',
        'user' => $_SESSION['prenom'] ?? 'Inconnu',
        'module' => 'conjugaison_verbe', 
    ];

    // Si une question est concernée, l'ajouter au log
    if (isset($sessionData['question_numero'])) {
        $logEntry['question_numero'] = $sessionData['question_numero'];
    }

    // Gestion des événements
    if ($nomPage == 'index.php' && $logEntry['method'] == 'GET') {
        $logEntry['event'] = 'DÉBUT SESSION';
    }
    if ($nomPage == 'fin.php') {
        $logEntry['event'] = 'FIN SESSION';
    }
    if ($nomPage == 'correction.php' && isset($sessionData['question_numero'])) {
        $logEntry['event'] = "Q" . $sessionData['question_numero'] . " corrigée";
    }
    if ($nomPage == 'question.php' && empty($sessionData['question_numero'])) {
        $logEntry['event'] = "Accès irrégulier";
    }

    // Ajouter le module dans le log
    if (isset($_SESSION['module'])) {
        $logEntry['module'] = $_SESSION['module']; 
    }

    // Lire le fichier JSON existant
    $logs = [];
    if (file_exists($cheminFichierLog)) {
        $contenu = file_get_contents($cheminFichierLog);
        if (!empty($contenu)) {
            $logs = json_decode($contenu, true) ?? [];
        }
    }

    // Ajouter le nouvel événement
    $logs[] = $logEntry;

    // Ajouter le score global à la fin du fichier JSON 
    if ($nomPage == 'fin.php' && isset($_SESSION['nbBonneReponse'])) {
        $logs[] = [
            'score_global' => $_SESSION['nbBonneReponse'] , // Ajout du score global à la fin
            'module' => 'conjugaison_verbe', 
        ];
    }

    // Réécrire le fichier JSON avec les logs mis à jour
    file_put_contents($cheminFichierLog, json_encode($logs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}


?>

<?php
function supprime_caracteres_speciaux($chaine) { 
    $chaine=str_replace("à","a",$chaine);
    $chaine=str_replace("â","a",$chaine);
    $chaine=str_replace("é","e",$chaine);
    $chaine=str_replace("è","e",$chaine);
    $chaine=str_replace("ë","e",$chaine);
    $chaine=str_replace("ê","e",$chaine);
    $chaine=str_replace("î","i",$chaine);
    $chaine=str_replace("ï","i",$chaine);
    $chaine=str_replace("ô","o",$chaine);
    $chaine=str_replace("ö","o",$chaine);
    $chaine=str_replace("ù","u",$chaine);
    $chaine=str_replace("û","u",$chaine);
    $chaine=str_replace("ü","u",$chaine);
    $chaine=str_replace("ÿ","y",$chaine);
    $chaine=str_replace("ç","c",$chaine);
    return $chaine;
}
?>

<?php
function conjugaison($nomFichier, $numLigne) {
    $fichierVerbe = file($nomFichier);
    $reponse = $fichierVerbe[$numLigne-1];
    $reponse = substr($reponse,0,-1);
    return $reponse;
}
?>
