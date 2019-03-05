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
// Année expo
if(isset($_GET['an']) && strlen($_GET['an']) == 4)
{
  $year = $_GET['an'];
} else {
  $year = date('Y');
}

require_once('inc/nav.inc.php');

/*************************/
/* LISTE DES EXPOSITIONS */
/*************************/
// Var init
$yearList = "";
$currentExpo = "";
$futurExpo = "";
$pastExpo = "";
$pastYearExpo = "";
$futurYearExpo = "";
$selected = "";

// Gestion année expo
$sql0 = "SELECT DISTINCT YEAR(debut_expo) AS an_expo
FROM expositions;";
$qry0 = $db->query($sql0);
foreach ($qry0 as $an)
{
  if($an['an_expo'] == $year) { $selected = " selected"; }
  $yearList .= "\t\t\t\t<option value=\"{$an['an_expo']}\"{$selected}>{$an['an_expo']}</option>\r\n";
}

if($year == date('Y'))
{
  // Expo en cours
  $sql1 = "SELECT titre_expo, debut_expo, fin_expo, id_expo
  FROM expositions
  WHERE CURDATE() BETWEEN debut_expo AND fin_expo;";
  $qry1 = $db->query($sql1);
  foreach ($qry1 as $val1)
  {
    $currentExpoVal = "\t\t\t\t\t<li><strong>{$val1['titre_expo']}</strong><br><small>Du " . frenchDate($val1['debut_expo']) . " au " . frenchDate($val1['fin_expo']) . "</small><br><span><a href=\"editer-expo.php?expo={$val1['id_expo']}\" title=\"Éditer\"><button>Éditer</button></a><a href=\"fiche-detail.php?expo={$val1['id_expo']}\" title=\"Programme\"><button>Programme</button></a><span></li>\r\n";
  }

  if(!empty($currentExpoVal))
  {
    $currentExpo .= "\t\t\t<h2>En cours</h2>\r\n";
    $currentExpo .= "\t\t\t\t<ul class=\"list\">\r\n";
    $currentExpo .= $currentExpoVal;
    $currentExpo .= "\t\t\t\t</ul>\r\n";
  }

  // Expos à venir
  $futurExpoVal = "";
  $sql2 = "SELECT titre_expo, debut_expo, fin_expo, id_expo
  FROM expositions
  WHERE CURDATE() < debut_expo
  AND YEAR(debut_expo) = YEAR(CURDATE())
  ORDER BY debut_expo;";
  $qry2 = $db->query($sql2);
  foreach ($qry2 as $val2)
  {
    $futurExpoVal .= "\t\t\t\t\t<li><strong>{$val2['titre_expo']}</strong><br><small>Du " . frenchDate($val2['debut_expo']) . " au " . frenchDate($val2['fin_expo']) . "</small><br><span><a href=\"editer-expo.php?expo={$val2['id_expo']}\" title=\"Éditer\"><button>Éditer</button></a><a href=\"fiche-detail.php?expo={$val2['id_expo']}\" title=\"Programme\"><button>Programme</button></a><span></li>\r\n";
  }

  if(!empty($futurExpoVal))
  {
    $futurExpo .= "\t\t\t<h2>À venir</h2>\r\n";
    $futurExpo .= "\t\t\t\t<ul class=\"list\">\r\n";
    $futurExpo .= $futurExpoVal;
    $futurExpo .= "\t\t\t\t</ul>\r\n";
  }

  // Expos passées
  $pastExpoVal = "";
  $sql3 = "SELECT titre_expo, debut_expo, fin_expo, id_expo
  FROM expositions
  WHERE CURDATE() > fin_expo
  AND YEAR(fin_expo) = YEAR(CURDATE())
  ORDER BY debut_expo;";
  $qry3 = $db->query($sql3);
  foreach ($qry3 as $val3)
  {
    $pastExpoVal .= "\t\t\t\t\t<li><strong>{$val3['titre_expo']}</strong><br><small>Du " . frenchDate($val3['debut_expo']) . " au " . frenchDate($val3['fin_expo']) . "</small><br><span><a href=\"editer-expo.php?expo={$val1['id_expo']}\" title=\"Éditer\"><button>Éditer</button></a><a href=\"fiche-detail.php?expo={$val3['id_expo']}\" title=\"Programme\"><button>Programme</button></a><span></li>\r\n";
  }

  if(!empty($pastExpoVal))
  {
    $pastExpo .= "\t\t\t<h2>Passées</h2>\r\n";
    $pastExpo .= "\t\t\t\t<ul class=\"list\">\r\n";
    $pastExpo .= $pastExpoVal;
    $pastExpo .= "\t\t\t\t</ul>\r\n";
  }

} else if($year > date('Y')) {
  // Expos années à venir
  $sql4 = "SELECT titre_expo, debut_expo, fin_expo, id_expo
  FROM expositions
  WHERE YEAR(fin_expo) = YEAR(CURDATE()) + 1
  OR YEAR(debut_expo) = YEAR(CURDATE()) + 1
  ORDER BY debut_expo;";
  $qry4 = $db->query($sql4);
  foreach ($qry4 as $val4)
  {
    $futurYearExpoVal = "\t\t\t\t\t<li><strong>{$val4['titre_expo']}</strong><br><small>Du " . frenchDate($val4['debut_expo']) . " au " . frenchDate($val4['fin_expo']) . "</small><br><span><a href=\"editer-expo.php?expo={$val4['id_expo']}\" title=\"Éditer\"><button>Éditer</button></a><a href=\"fiche-detail.php?expo={$val4['id_expo']}\" title=\"Programme\"><button>Programme</button></a><span></li>\r\n";
  }

  if(!empty($futurYearExpoVal))
  {
    $futurYearExpo .= "\t\t\t\t<ul class=\"list\">\r\n";
    $futurYearExpo .= $futurYearExpoVal;
    $futurYearExpo .= "\t\t\t\t</ul>\r\n";
  }
} else {
  // Expos années passées
  $sql4 = "SELECT titre_expo, debut_expo, fin_expo, id_expo
  FROM expositions
  WHERE YEAR(debut_expo) = YEAR(CURDATE()) - 1
  OR YEAR(fin_expo) = YEAR(CURDATE()) - 1
  ORDER BY debut_expo;";
  $qry4 = $db->query($sql4);
  foreach ($qry4 as $val4)
  {
    $pastYearExpoVal = "\t\t\t\t\t<li><strong>{$val4['titre_expo']}</strong><br><small>Du " . frenchDate($val4['debut_expo']) . " au " . frenchDate($val4['fin_expo']) . "</small><br><span><a href=\"editer-expo.php?expo={$val4['id_expo']}\" title=\"Éditer\"><button>Éditer</button></a><a href=\"fiche-detail.php?expo={$val4['id_expo']}\" title=\"Programme\"><button>Programme</button></a><span></li>\r\n";
  }

  if(!empty($pastYearExpoVal))
  {
    $pastYearExpo .= "\t\t\t\t<ul class=\"list\">\r\n";
    $pastYearExpo .= $pastYearExpoVal;
    $pastYearExpo .= "\t\t\t\t</ul>\r\n";
  }
}
?>
<?php require_once('inc/head.inc.php'); ?>
          <form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="single-line">
            <p>
              <select id="an" name="an">
<?php echo $yearList; ?>
              </select>
              <input type="submit" value="LISTER">
            </p>
          </form>
<?php echo $currentExpo; ?>
<?php echo $futurExpo; ?>
<?php echo $pastExpo; ?>
<?php echo $pastYearExpo; ?>
<?php echo $futurYearExpo; ?>
<?php require_once('inc/foot.inc.php'); ?>
