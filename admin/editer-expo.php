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

/***************************/
/* ÉDITION DES EXPOSITIONS */
/***************************/
// Création de la variable de session + test de routage
if(isset($_GET['expo']) && !empty(intval($_GET['expo'])))
{
  $_SESSION['editExpo'] = intval($_GET['expo']);
}
if(isset($_SESSION['editExpo']))
{
  require_once('inc/edit-expo.inc.php');
} else {
  require_once('inc/create-expo.inc.php');
}
?>
<?php require_once('inc/head.inc.php'); ?>

<?php if(!isset($_SESSION['currentExpo']) && !isset($_GET['return'])) { ?>
          <!-- étape 1 -->
          <h2>Étape 1</h2>

          <?php if(isset($getFr['titre_expo'])) { echo "<h3>► {$getFr['titre_expo']}</h3>"; } ?>

          <?php echo $warning; ?>

          <form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" class="wide">
            <div class="box">
              <p><label for="titre">Titre* :</label> <input type="text" id="titre" name="titre" maxlength="500" value="<?php echo $titreVal ?>"></p>
              <p><label for="titre-en">Titre anglais :</label> <input type="text" id="titre-en" name="titre-en" maxlength="500" value="<?php echo $titreValEn ?>"></p>
              <p><label for="titre-zh">Titre chinois :</label> <input type="text" id="titre-zh" name="titre-zh" maxlength="500" value="<?php echo $titreValZh ?>"></p>
            </div>
            <div class="box">
              <p><label for="desc">Descriptif* :</label> <textarea id="desc" name="desc" maxlength="1000"><?php echo $descVal ?></textarea></p>
              <p><label for="desc-en">Descriptif anglais :</label> <textarea id="desc-en" name="desc-en" maxlength="1000"><?php echo $descValEn ?></textarea></p>
              <p><label for="desc">Descriptif chinois :</label> <textarea id="desc-zh" name="desc-zh" maxlength="1000"><?php echo $descValZh ?></textarea></p>
            </div>
            <div class="box">
              <p><label for="debut">Date début* :</label> <input type="date" id="debut" name="debut" value="<?php echo $debutVal ?>"></p>
              <p><label for="fin">Date fin* :</label> <input type="date" id="fin" name="fin" value="<?php echo $finVal ?>"></p>
            </div>
            <p>
              <input type="submit" name="expo1" id="expo1" value="Valider étape 1">
              <input type="submit" name="expo1-reset" id="expo1-reset" value="Annuler">
            </p>
          </form>

<?php } elseif (isset($_SESSION['currentExpo'])) { ?>
          <!-- étape 2 -->
          <h2>Étape 2</h2>

          <?php if(isset($_SESSION['titreCurrentExpo'])) { echo "<h3>► {$_SESSION['titreCurrentExpo']}</h3>"; } ?>

          <?php echo $warning; ?>

          <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <table id="expo-emp">
              <tr><th>Emplacement</th><th>Titre de l'oeuvre</th><th>Date de livraison</th></tr>
<?php echo $empTab; ?>
            </table>
            <p>
              <input type="submit" name="expo2" id="expo2" value="Valider étape 2">
              <input type="submit" name="expo2-reset" id="expo2-reset" value="Annuler">
            </p>
          </form>

<?php } elseif (isset($_GET['return']) && $_GET['return'] == "true") { ?>
          <!-- OK -->
          <h2>Validation</h2>
          <p class="warning">Votre programme a bien été enregistré !</p>
          <p><a href="editer-expo.php" title="retour">RETOUR</a></p>
<?php } else { ?>
          <!-- KO -->
          <h2>Erreur</h2>
          <p class="warning">Une erreur est survenue !</p>
          <p><a href="editer-expo.php" title="retour">RETOUR</a></p>
<?php } ?>

<?php require_once('inc/foot.inc.php'); ?>
