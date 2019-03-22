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

/******************************/
/* EDITION DES COLLABORATEURS */
/******************************/
// Code spécifique de la page

?>
<?php require_once('inc/head.inc.php'); ?>
          <!-- FRONT DE LA PAGE -->
  <form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" class="wide">
    <p><label for="nom">Nom* :</label> <input type="text" id="nom" name="nom" maxlength="500" value=""></p>
    <p><label for="prenom">Prénom* :</label> <input type="text" id="prenom" name="prenom" maxlength="500" value=""></p>
    <p><label for="email">Email :</label> <input type="text" id="email" name="email" maxlength="500" value=""></p>
    <p><label for="trigramme">Trigramme :</label> <input type="text" id="trigramme" name="trigramme" maxlength="500" value=""></p>
    <p>
      <input type="submit" name="editerCollaborrateur" id="editCollab" value="Valider">
      <input type="submit" name="editerCollaborrateur-reset" id="editCollab-reset" value="Annuler">
    </p>
  </form>
<?php require_once('inc/foot.inc.php'); ?>
