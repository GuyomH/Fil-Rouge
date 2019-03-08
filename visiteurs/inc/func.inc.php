<?php
// Formatage de la date en français
function frenchDate($date)
{
  return date("d/m/Y", strtotime($date));
}

if (isset($_GET['id']))
{
  $param = "&id=" . intval($_GET['id']);
  $param2 = "?id=" . intval($_GET['id']);
} else {
  $param = $param2 = "";
}
?>
