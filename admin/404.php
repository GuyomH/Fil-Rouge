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
require_once('inc/nav.inc.php');

/*******************/
/* CODE SPECIFIQUE */
/*******************/
$hTitle = "ERREUR 404";
//echo realpath('404.php');
?>
<?php require_once('inc/head.inc.php'); ?>
          <h2>La page demandée n'existe pas !</h2>
          <p><a href="index.php">Retour à l'accueil</a></p>
<?php require_once('inc/foot.inc.php'); ?>
