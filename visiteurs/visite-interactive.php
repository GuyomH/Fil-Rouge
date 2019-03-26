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

/**********************************/
/* DESCRIPTIF DE  L'EXPO EN COURS */
/**********************************/
$q1 = $db->query($sql['info_expo']);

$expoEnCours = "";

foreach($q1 as $val)
{
  if(!isset($_COOKIE['lang']) || $_COOKIE['lang'] == "fr")
  {
    $titreExpo = $val['titre_expo'];
    $descriptifExpo = $val['descriptif_expo'];
  } else {
    $titreExpo = $val['titre_expo_trad'];
    $descriptifExpo = $val['descriptif_expo_trad'];
  }
  $expoEnCours .= "\t\t<h1>{$titreExpo}</h1>\r\n";
  $expoEnCours .= "\t\t<p class=\"texte\">{$descriptifExpo}</p>\r\n";
}

if(empty($expoEnCours))
{
  // si pas d'expo en cours redirection vers l'index
  header('Location: index.php');
}

/*****************************************/
/* LISTE DES OEUVRES - CARTE INTERACTIVE */
/*****************************************/
$q2 = $db->query($sql['liste_oeuvre']);

// Var init
$listOeuvre = "";
$listPuce = "";
$hr = FALSE;

foreach($q2 as $val)
{
  if(!isset($_COOKIE['lang']) || $_COOKIE['lang'] == "fr")
  {
    $titreOeuvre = $val['titre_oeuvre'];
    $empOeuvre = $val['num_emp'];
    $nomArt = $val['nom_art'];
    $prenomArt = $val['prenom_art'];
    $nomCol = $val['nom_col'];
    $idOeuvre=$val['id_oeuvre'];
  } else {
    $titreOeuvre = $val['titre_oeuvre_trad'];
    $empOeuvre = $val['num_emp'];
    $nomArt = $val['nom_art'];
    $prenomArt = $val['prenom_art'];
    $nomCol = $val['nom_col'];
    $idOeuvre=$val['id_oeuvre'];
  }

  if($hr)
  {
    $listOeuvre .= "\t\t<hr>\r\n";
  }

  $hr = TRUE;
  $listOeuvre .= "\t\t<div class=\"container\">\r\n";
  $listOeuvre .= "\t\t\t<ul id='emp{$empOeuvre}'>\r\n";
  $listOeuvre .= "\t\t\t\t\t<li>{$itf['titre_oeuvre']} : <strong>{$titreOeuvre}</strong></li>\r\n";
  $listOeuvre .= "\t\t\t\t<li>{$itf['emplacement']} : <strong>{$empOeuvre}</strong></li>\r\n";
  if($nomCol!="")
  {
    $listOeuvre .= "\t\t\t\t<li>{$itf['collectif']} : <strong>{$nomCol}</strong></li>\r\n";
  }

  if($nomArt != "")
  {
      $listOeuvre .= "\t\t\t\t<li>{$itf['artiste']} : <strong>{$prenomArt} {$nomArt}</strong></li>\r\n";
  }

  $listOeuvre .= "\t\t\t\t<li><a href='fiche.php?id=$idOeuvre'><button>{$itf['fiche_detail']}</button></a></li>\r\n";
  $listOeuvre .= "\t\t\t\t<li><a href='#plan'>{$itf['retour_plan']}</a></li>\r\n";
  $listOeuvre .= "\t\t\t</ul>\r\n";
  $listOeuvre .= "\t\t</div>\r\n";

  //generation des puces
  $listPuce .= "\t\t\t<a href=\"#emp{$empOeuvre}\" title=\"{$empOeuvre}\" id=\"e{$empOeuvre}\" class=\"puce\"><div></div></a>\r\n";
}

if(empty($listOeuvre))
{
  $listOeuvre .= "\t\t<h3>{$itf['no_expo']}</h3>\r\n";
}
?>
<?php
/**********/
/* HEADER */
/**********/
require_once('inc/head.inc.php');
?>

    <main id="visite">
      <div class="container">
<?php echo $expoEnCours; ?>
      </div>
      <div class="container">
        <h2 id="plan"><?php echo $itf['plan_int']; ?></h2>
        <div id="map">
          <img src="img/plan.png" alt="plan">
<?php echo $listPuce; ?>
        </div>
      </div>
      <div class="container">
        <h2><?php echo $itf['liste_oeuvre']; ?></h2>
      </div>
<?php echo $listOeuvre; ?>
    </main>

<?php
/**********/
/* FOOTER */
/**********/
require_once('inc/foot.inc.php');
?>
