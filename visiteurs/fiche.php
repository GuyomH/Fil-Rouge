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

/*******************/
/* FICHE DÉTAILLÉE */
/*******************/
$q1 = $db->query($sql['fiche_detail']);

$OeuvreDetail = "";

foreach($q1 as $val)
{
  if(!isset($_COOKIE['lang']) || $_COOKIE['lang'] == "fr")
  {
    $titreOeuvre = $val['titre_oeuvre'];
    $descriptifOeuvre = $val['descriptif_oeuvre'];
    $nomArt = $val['nom_art'];
    $prenomArt = $val['prenom_art'];
    $nomCol = $val['nom_col'];
    $infoCol = $val['info_col'];
    $bioArt = $val['bio_art'];
    $idOeuvre = $val['id_oeuvre'];
    $idArtiste = $val['id_art'];
    $idCollectif = $val['id_col'];
  } else {
    $titreOeuvre = $val['titre_oeuvre_trad'];
    $descriptifOeuvre = $val['descriptif_oeuvre_trad'];
    $nomArt = $val['nom_art'];
    $prenomArt = $val['prenom_art'];
    $nomCol = $val['nom_col'];
    $infoCol = $val['info_col_trad'];
    $bioArt = $val['bio_art_trad'];
    $idOeuvre = $val['id_oeuvre'];
    $idArtiste = $val['id_art'];
    $idCollectif = $val['id_col'];
  }
  $OeuvreDetail .= "\t\t<div class='fiche'>\r\n";
  $OeuvreDetail .= "\t\t<h1>{$titreOeuvre}</h1>\r\n";
  $OeuvreDetail .= "\t\t\t<ul>\r\n";
  $OeuvreDetail .= "\t\t\t\t<li><strong>{$descriptifOeuvre}</strong></li>\r\n";

  if(($nomCol != "") && ($nomArt != ""))
  {
    if (($_COOKIE['lang']!= "fr") && empty($infoCol) && empty($bioArt))
    {
      $OeuvreDetail .= "\t\t\t\t<li><strong><h2>{$nomCol} / {$prenomArt} {$nomArt}</h2></strong> <button class=\"toggle\">˅</button></li>\r\n";
      $OeuvreDetail .= "\t\t\t\t<li class=\"content\"><img src='../collectifs/{$idCollectif}.jpg'/><p><strong>{$itf['no_trad']}</strong></p><img src='../artistes/{$idArtiste}.jpg'/><p><strong>{$itf['no_trad']}</strong></p></li>\r\n";
    } else {
      $OeuvreDetail .= "\t\t\t\t<li><strong><h2>{$nomCol} / {$prenomArt} {$nomArt}</h2></strong> <button class=\"toggle\">˅</button></li>\r\n";
      $OeuvreDetail .= "\t\t\t\t<li class=\"content\"><img src='../collectifs/{$idCollectif}.jpg'/><p><strong>{$infoCol}</strong></p><img src='../artistes/{$idArtiste}.jpg'/><p><strong>{$bioArt}</strong></p></li>\r\n";
    }

  }elseif (($nomCol == "") && ($nomArt != ""))
  {
    if (($_COOKIE['lang']!= "fr") && empty($bioArt))
    {
      $OeuvreDetail .= "\t\t\t\t<li><strong><h2>{$prenomArt} {$nomArt}</h2></strong> <button class=\"toggle\">˅</button></li>\r\n";
      $OeuvreDetail .= "\t\t\t\t<li class=\"content\"><img src='../artistes/{$idArtiste}.jpg'/><p><strong>{$itf['no_trad']}</strong></p></li>\r\n";
    } else {
      $OeuvreDetail .= "\t\t\t\t<li><strong><h2>{$prenomArt} {$nomArt}</h2></strong> <button class=\"toggle\">˅</button></li>\r\n";
      $OeuvreDetail .= "\t\t\t\t<li class=\"content\"><img src='../artistes/{$idArtiste}.jpg'/><p><strong>{$bioArt}</strong></p></li>\r\n";
    }
  }else {
    if (($_COOKIE['lang']!= "fr") && empty($nomCol))
    {
      $OeuvreDetail .= "\t\t\t\t<li><strong><h2>{$nomCol}</h2></strong> <button class=\"toggle\">˅</button></li>\r\n";
      $OeuvreDetail .= "\t\t\t\t<li class=\"content\"><img src='../collectifs/{$idCollectif}.jpg'/><p><strong>{$itf['no_trad']}</strong></p></li>\r\n";
    } else {
      $OeuvreDetail .= "\t\t\t\t<li><strong><h2>{$nomCol}</h2></strong> <button class=\"toggle\">˅</button></li>\r\n";
      $OeuvreDetail .= "\t\t\t\t<li class=\"content\"><img src='../collectifs/{$idCollectif}.jpg'/><p><strong>{$infoCol}</strong></p></li>\r\n";
    }
  }
  $OeuvreDetail .= "\t\t\t</ul>\r\n";
}
$OeuvreDetail .= "\t\t</div>\r\n";

if(empty($OeuvreDetail))
{
  $OeuvreDetail .= "\t\t<h3>{$itf['no_expo']}</h3>\r\n";
  // Si pas d'expo en cours, le lien vers la visite est désactivée
  $visitLnk = "<span class=\"disabled\" title=\"Pas de visite intéractive pour le moment\">{$itf['visite']}</span>";
}

/*********/
/* Media */
/*********/
$q2 = $db->query($sql['media']);

$listMedia = "";

foreach($q2 as $val)
{
  $nomMedia = $val['nom_media'];
  $typeMedia = $val['type_media'];
  $idExpo = $val['id_expo'];

  if ($typeMedia == "image")
  {
    $listMedia .= "\t\t<img src='../media/{$idExpo}/{$nomMedia}'/>\r\n";
  } else if ($typeMedia == "video") {
    $listMedia .= "\t\t<video controls src='../media/{$idExpo}/{$nomMedia}'></video>\r\n";
  } else {
    $listMedia .= "\t\t<div class='audio'><audio controls src='../media/{$idExpo}/{$nomMedia}'></audio></div>\r\n";
  }
}
?>
<?php
/**********/
/* HEADER */
/**********/
require_once('inc/head.inc.php');
?>

    <main id="fiche">
      <div class="container margin-bottom">
        <!-- <h1><?php echo $itf['oeuvre']; ?></h1> -->
        <?php echo $OeuvreDetail; ?>
        <h2><?php echo $itf['media']; ?> <br><button class="toggle">˅</button></h2>
        <div class="content"><?php echo $listMedia; ?></div>
        <a href='visite-interactive.php' title='visite_interactive'><button><?php echo $itf['retour_visite'];?></button></a>
      </div>
    </main>

<?php
/**********/
/* FOOTER */
/**********/
require_once('inc/foot.inc.php');
?>
