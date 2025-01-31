<?php
$menuArray = // Structuration du menu sous forme de tableau
[
  ['CONNEXION', 'index.php', 'Connexion / Déconnexion'],

  [
    'EXPOSITIONS',
    ['Liste', 'liste-expo.php', 'Liste des expositions'],
    ['Créer / Éditer', 'editer-expo.php', 'Créer ou éditer une exposition']
  ],

  [
    'GESTION',
    ['Collectifs', 'editer-collectif.php', 'Créer ou éditer un collectif'],
    ['Artistes', 'editer-artiste.php', 'Créer ou éditer un artiste'],
    ['Oeuvres', 'editer-oeuvre.php', 'Créer ou éditer une oeuvre']
  ],

  [
    'COLLABORATEURS',
    ['Liste', 'liste-collab.php', 'Liste des collaborateurs'],
    ['Ajouter', 'editer-collab.php', 'Créer ou éditer un collaborateur']
  ],

  /*['TRADUCTIONS', 'traduction.php', 'Trouver les contenus non traduits'],*/

  ['STATISTIQUES', 'statistique.php', 'Statistiques de consultation de la partie publique du site']
];

// NAV START
$nav = "\t\t<ul>\r\n";
for($i = 0; $i < count($menuArray); $i++)
{
  if(isset($_SESSION['identification']))
  {
    // PRIVILEGES ADMIN (MENU COLLAB)
    if(($role != "administrateur") && ($menuArray[$i][0] == 'COLLABORATEURS')) { continue; }
    // Chgmnt valeur rubrique CONNEXION
    $menuArray[0][0] = "DÉCONNEXION";
  } else {
    // AFFICHAGE MENU CONNEXION SEUL
    if($i > 0) { break; }
  }
  // RUBRIQUES SIMPLES
  if(!is_array($menuArray[$i][1]))
  {
    if(basename($_SERVER['PHP_SELF']) == $menuArray[$i][1])
    {
      $nav .= "\t\t\t<li><span class=\"activeMenu\">{$menuArray[$i][0]}</span></li>\r\n";
    } else {
      $nav .= "\t\t\t<li><a href=\"{$menuArray[$i][1]}\" title=\"{$menuArray[$i][2]}\" >{$menuArray[$i][0]}</a></li>\r\n";
    }
  // RUBRIQUES AVEC SOUS RUBRIQUES
  } else {
    $nav .= "\t\t\t<li>\r\n";
    $nav .= "\t\t\t\t<div class=\"menu-titre\">{$menuArray[$i][0]}</div>\r\n";
    $nav .= "\t\t\t\t<ul>\r\n";
    // $j commence à 1 pour exclure le titre
    for($j = 1; $j < count($menuArray[$i]); $j++)
    {
      if(basename($_SERVER['PHP_SELF']) == $menuArray[$i][$j][1])
      {
        $nav .= "\t\t\t\t\t<li><span class=\"activeSubMenu\">{$menuArray[$i][$j][0]}</span></li>\r\n";
      } else {
        $nav .= "\t\t\t\t\t<li><a href=\"{$menuArray[$i][$j][1]}\" title=\"{$menuArray[$i][$j][2]}\">{$menuArray[$i][$j][0]}</a></li>\r\n";
      }
    }
    $nav .= "\t\t\t\t</ul>\r\n";
    $nav .= "\t\t\t</li>\r\n";
  }
}
$nav .= "\t\t</ul>\r\n";
// NAV END

// GESTION DES TITRES
switch (basename($_SERVER['PHP_SELF']))
{
  case 'index.php':
    if(isset($_SESSION['identification']))
    {
      $title = $hTitle = "DÉCONNEXION";
    } else {
      $title = $hTitle = "CONNEXION";
    }
    break;
  case 'liste-expo.php':
    $title = $hTitle = "LISTE DES EXPOSITIONS " . $year;
    break;
  case 'editer-expo.php':
    if(isset($_SESSION['editExpo']) || isset($_GET['expo']))
    {
      $title = $hTitle = "ÉDITER UNE EXPOSITION";
    } else {
      $title = $hTitle = "CRÉER UNE EXPOSITION";
    }
    break;
  case 'editer-oeuvre.php':
    if(isset($_SESSION['loadedOvr']) || (isset($_POST['select-ovr']) && ($_POST['select-ovr'] > 0)))
    {
      $title = $hTitle = "ÉDITER UNE OEUVRE";
    } else {
      $title = $hTitle = "CRÉER UNE OEUVRE";
    }
    break;
  case 'editer-artiste.php':
    if(isset($_SESSION['loadedArt']) || (isset($_POST['select-art']) && ($_POST['select-art'] > 0)))
    {
      $title = $hTitle = "ÉDITER UN ARTISTE";
    } else {
      $title = $hTitle = "CRÉER UN ARTISTE";
    }
    break;
  case 'editer-collectif.php':
    if(isset($_SESSION['loadedColl']) || (isset($_POST['select-coll']) && ($_POST['select-coll'] > 0)))
    {
      $title = $hTitle = "ÉDITER UN COLLECTIF";
    } else {
      $title = $hTitle = "CRÉER UN COLLECTIF";
    }
    break;
  case 'liste-collab.php':
    $title = $hTitle = "LISTE DES COLLABORATEURS";
    break;
  case 'editer-collab.php':
    // $title = $hTitle = "AJOUTER UN COLLABORATEUR";
    if(isset($_GET['collab']) && !empty($_GET['collab']) || isset($_SESSION['collab']))
    {
      $title = $hTitle = "ÉDITER UN COLLABORATEUR";
    } else {
      $title = $hTitle = "AJOUTER UN COLLABORATEUR";
    }
    break;
  case 'traduction.php':
    $title = $hTitle = "CONTENUS NON TRADUITS";
    break;
  case 'statistique.php':
    $title = $hTitle = "STATISTIQUES";
    break;
  case 'fiche-detail.php':
    $title = $hTitle = "PROGRAMME";
    break;
  default:
    break;
}
// GESTION DES TITRES / FIN
?>
