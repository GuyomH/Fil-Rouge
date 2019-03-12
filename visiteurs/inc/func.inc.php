<?php
// Formatage de la date en français
function frenchDate($date)
{
  return date("d/m/Y", strtotime($date));
}

// Gestion de la valeur par défaut du $_COOKIE
if(!isset($_COOKIE['lang'])) { $_COOKIE['lang'] = "fr"; }

// Gestion du paramètre ID
if (isset($_GET['id']))
{
  $param = "&id=" . intval($_GET['id']);
  $param2 = "?id=" . intval($_GET['id']);
} else {
  $param = $param2 = "";
}
?>
