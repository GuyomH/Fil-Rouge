<?php
// Formatage de la date en français
function frenchDate($date)
{
  return date("d/m/Y", strtotime($date));
}
?>
