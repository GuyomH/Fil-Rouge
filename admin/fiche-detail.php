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
$sql = "SELECT E.titre_expo, EMP.num_emp, titre_oeuvre, O.id_oeuvre, livraison_oeuvre
FROM oeuvres AS O
INNER JOIN composer AS C ON O.id_oeuvre = C.id_oeuvre
INNER JOIN expositions AS E ON C.id_expo = E.id_expo
INNER JOIN emplacements AS EMP ON C.num_emp = EMP.num_emp
WHERE E.id_expo = $idExpo
ORDER BY EMP.num_emp;";

// URL de base
// $baseUrl = str_replace
// (
//   basename($_SERVER['PHP_SELF']),
//   "",
//   "http://{$_SERVER['HTTP_HOST']}{$_SERVER['PHP_SELF']}"
// );
$baseUrl = "http://localhost/php/Fil-Rouge/visiteurs/";

$listing = "\t\t\t<ul id=\"prog\">\r\n";
$qry = $db->query($sql);
foreach ($qry as $val)
{
  $url = urlEncode($baseUrl."fiche.php?id=".$val['id_oeuvre']);
  $listing .= "\t\t\t\t<li><h3>{$val['num_emp']} : {$val['titre_oeuvre']}<h3>
  <img src=\"https://api.qrserver.com/v1/create-qr-code/?data={$url}&amp;format=svg\" alt=\"\" title=\"\" /></li>\r\n";
}
$listing .= "\t\t\t</ul>\r\n";
$titre = $val['titre_expo'];
?>
<?php require_once('inc/head.inc.php'); ?>

        <h2><?php echo $titre; ?></h2>

<?php echo $listing; ?>

<?php require_once('inc/foot.inc.php'); ?>
