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

// Générateur de mots de passe
function pwdGen()
{
  $chars = array("a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s",
   "t","u","v","w","x","y","z","A","B","C","D","E","F","G","H","I","J","K","L","M",
  "N","O","P","Q","R","S","T","U","V","W","X","Y","Z","0","1","2","3","4","5","6",
  "7","8","9","&","#","@","+","-","*","!","=","$","%",".",",",";");
  $indexMax = count($chars) - 1;
  $pwd = "";
  for($i = 0 ; $i < 10; $i++)
  {
    $pwd .= $chars[rand(0,  $indexMax)];
  }
  return $pwd;
}
?>
