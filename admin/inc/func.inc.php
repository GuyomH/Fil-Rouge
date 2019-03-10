<?php
// Formatage de la date en français
function frenchDate($date)
{
  return date("d/m/Y", strtotime($date));
}

// Vérification de la date au format (YYYY-MM-DD)
function dateChecker($date)
{
  $split = explode("-", $date);
  $year = $split[0];
  $month = $split[1];
  $day = $split[2];
  return checkdate ($month, $day, $year);
}
?>
