<?php
/****************/
/* CONNEXION DB */
/****************/
require_once('inc/pdo.inc.php');

/********************/
/* FONCTIONS UTILES */
/********************/
require_once('inc/func.inc.php');

/***********************/
/* GESTION DES LANGUES */
/***********************/
require_once('inc/lang.inc.php');

/******************************************/
/* GESTION DE LA NAVIGATION & DE L'ENTETE */
/******************************************/
require_once('inc/nav.inc.php');

/*****************/
/* EXPO EN COURS */
/*****************/
$q1 = $db->query($sql['expo_en_cours']);

$expoEnCours = "";

foreach($q1 as $val)
{
  if(!isset($_COOKIE['lang']) || $_COOKIE['lang'] == "fr")
  {
    $titreExpo = $val['titre_expo'];
  } else {
    $titreExpo = $val['titre_expo_trad'];
  }
  $expoEnCours .= "\t\t<h3>{$titreExpo}</h3>\r\n";
  $expoEnCours .= "\t\t<p class=\"date\">{$itf['du']} " . frenchDate($val['debut_expo']) . " {$itf['au']} " . frenchDate($val['fin_expo']) . "{$itf['au_bis']}</p>\r\n";
  $expoEnCours .= "\t\t<p><a href=\"visite-interactive.php\" title=\"Visite interactive de l'exposition en cours\"><button>{$itf['decouvrir']}</button></a></p>\r\n";
}

if(empty($expoEnCours))
{
  $expoEnCours .= "\t\t<h3>{$itf['no_expo']}</h3>\r\n";
  // Si pas d'expo en cours, le lien vers la visite est désactivée
  $visitLnk = "<span class=\"disabled\" title=\"Pas de visite intéractive pour le moment\">{$itf['visite']}</span>";
}

/****************/
/* EXPO A VENIR */
/****************/
$q2 = $db->query($sql['expo_a_venir']);

$expoAVenir = "";
$hr = FALSE;

foreach($q2 as $val)
{
  if(!isset($_COOKIE['lang']) || $_COOKIE['lang'] == "fr" )
  {
    $titreExpo = $val['titre_expo'];
  } else {
    $titreExpo = $val['titre_expo_trad'];
  }
  if($hr)
  {
    $expoAVenir .= "\t\t<hr>\r\n";
  }
  $hr = TRUE;
  $expoAVenir .= "\t\t<div class=\"container\">\r\n";
  $expoAVenir .= "\t\t\t<h3>{$titreExpo}</h3>\r\n";
  $expoAVenir .= "\t\t\t<p class=\"date\">{$itf['du']} " . frenchDate($val['debut_expo']) . " {$itf['au']} " . frenchDate($val['fin_expo']) . "{$itf['au_bis']}</p>\r\n";
  $expoAVenir .= "\t\t</div>\r\n";
}

if(empty($expoAVenir))
{
  $expoAVenir .= "\t\t<div class=\"container\">\r\n";
  $expoAVenir .= "\t\t\t<h2>{$itf['no_expo_a_venir']}</h2>\r\n";
  $expoAVenir .= "\t\t</div>\r\n";
}
?>
<?php
/**********/
/* HEADER */
/**********/
require_once('inc/head.inc.php');
?>

    <main id="index">
      <div class="container margin-bottom">
        <h1><?php echo $itf['expo_en_cours']; ?></h1>
<?php echo $expoEnCours; ?>
      </div>
      <div class="container">
        <h2><?php echo $itf['expo_a_venir']?></h2>
      </div>
<?php echo $expoAVenir; ?>
    </main>

<?php
/**********/
/* FOOTER */
/**********/
require_once('inc/foot.inc.php');
?>
