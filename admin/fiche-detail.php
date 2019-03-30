<?php
/*************************/
/* GESTION DE LA SESSION */
/*************************/
require_once('inc/session.inc.php');

/****************/
/* CONNEXION DB */
/****************/
require_once('inc/pdo.inc.php');

/********************/
/* FONCTIONS UTILES */
/********************/
require_once('inc/func.inc.php');

/****************************/
/* GESTION DE LA NAVIGATION */
/****************************/
require_once('inc/nav.inc.php');

/********************************/
/* FICHE DE DETAIL DU PROGRAMME */
/********************************/
// Récupération de l'ID d'expo
if(isset($_GET['expo']) && !empty(intval($_GET['expo'])))
{
  $idExpo = intval($_GET['expo']);
} else {
  header('Location: liste-expo.php.php');
  exit();
}

// Listing du programme de l'expo
$sql = "SELECT E.titre_expo, E.debut_expo, E.fin_expo, EMP.num_emp, titre_oeuvre, O.id_oeuvre, livraison_oeuvre
FROM oeuvres AS O
INNER JOIN composer AS C ON O.id_oeuvre = C.id_oeuvre
INNER JOIN expositions AS E ON C.id_expo = E.id_expo
INNER JOIN emplacements AS EMP ON C.num_emp = EMP.num_emp
WHERE E.id_expo = $idExpo
ORDER BY EMP.num_emp;";

$baseUrl = "http://{$ip}/php/Fil-Rouge/visiteurs/";

$listing = "\t\t\t<ul id=\"prog\">\r\n";
$qry = $db->query($sql);
foreach ($qry as $val)
{
  // Récupération de l'image
  $sql2 = "SELECT nom_media FROM medias AS M
           INNER JOIN accompagner AS A ON M.id_media = A.id_media
           WHERE id_oeuvre = {$val['id_oeuvre']}
           AND type_media = 'image'
           LIMIT 1;";
  $qry2 = $db->query($sql2);
  $image = $qry2->fetch();
  // Affichage des éléments
  $url = urlEncode($baseUrl."fiche.php?id=".$val['id_oeuvre']);
  $listing .= "\t\t\t\t<li>
  \t\t\t\t\t<h3>{$val['num_emp']} : {$val['titre_oeuvre']}</h3>
  \t\t\t\t\t<img src=\"https://api.qrserver.com/v1/create-qr-code/?data={$url}&amp;format=svg\" alt=\"QR CODE\" />
  \t\t\t\t\t<img src=\"../media/{$val['id_oeuvre']}/{$image['nom_media']}\" alt=\"\" />
  \t\t\t\t</li>\r\n";
}
$listing .= "\t\t\t</ul>\r\n";
$debut = frenchDate($val['debut_expo']);
$fin = frenchDate($val['fin_expo']);
$titre = "{$val['titre_expo']} <wbr><i>({$debut} - {$fin})</i>";
?>
<?php require_once('inc/head.inc.php'); ?>

        <h2><?php echo $titre; ?></h2>

<?php echo $listing; ?>

<?php require_once('inc/foot.inc.php'); ?>
