<?php
// Valeurs par défaut
$indexLnk = "<a href=\"index.php\" title=\"Page d'accueil\">{$itf['accueil']}</a>";
$visitLnk = "<a href=\"visite-interactive.php\" title=\"plan intéractif de l'exposition en cours\">{$itf['visite']}</a>";

if(stristr($_SERVER['PHP_SELF'], 'index.php'))
{
  // Index
  $indexLnk = "<span class=\"active\" title=\"page courante\">► {$itf['accueil']}</span>";
  $title="Grand Angle : Accueil";
  $desc="Grand Angle : Accueil";
  $pageName = "index";
} elseif (stristr($_SERVER['PHP_SELF'], 'visite-interactive.php')) {
  // Visite intéractive
  $visitLnk = "<span class=\"active\" title=\"page courante\">► {$itf['visite']}</span>";
  $title="Grand Angle : Visite intéractive";
  $desc="Grand Angle : Visite intéractive";
  $pageName = "visite-interactive";
} elseif (stristr($_SERVER['PHP_SELF'], 'fiche.php')) {
  // Fiche détaillée
  $title="Grand Angle : Fiche détaillée";
  $desc="Grand Angle : Fiche détaillée";
  $pageName = "fiche";
} else {
  // 404
  $title="Grand Angle : Erreur 404";
  $desc="Grand Angle : Erreur 404";
  $pageName = "index";
}
?>
