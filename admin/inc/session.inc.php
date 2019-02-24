<?php
session_start();

if(isset($_POST['destroy']))
{
  session_destroy();
  header('Location: index.php');
  exit();
}

if(isset($_SESSION['identification']))
{
  $getVal = json_decode($_SESSION['identification'], true); // paramètre true = array
  $trigramme = $getVal["trigramme"];
  $role = ($getVal["role"] == "admin")?"administrateur":"utilisateur";
  $nom = $getVal["nom"];
  $prenom = $getVal["prenom"];
} elseif(basename($_SERVER['PHP_SELF']) != "index.php") {
    header('Location: index.php');
    exit();
}
?>
