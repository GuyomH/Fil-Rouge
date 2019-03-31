<?php
// IP réelle + port (via ipconfig)
$ip = "192.168.43.65:80";

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

// Fonction pour retirer les accents
function stripAccents($string)
{
  $search = array("à","á","â","ã","ä","ç","è","é","ê","ë","ì","í","î","ï","ñ","ò","ó","ô","õ","ö","ù","ú","û","ü","ý","ÿ");
  $replace = array("a","a","a","a","a","c","e","e","e","e","i","i","i","i","n","o","o","o","o","o","u","u","u","u","y","y");
  return str_replace($search, $replace, $string);
}

// Générateur de trigrammes
// 1ère lettre du nom / 1ère et dernière lettre du prénom
function triGen($nom, $prenom)
{
  $trig = mb_substr(trim($nom), 0, 1);
  $trig .= mb_substr(trim($prenom), 0, 1);
  $trig .= mb_substr(trim($prenom), -1, 1);
  return strToLower(stripAccents($trig));
}
?>
