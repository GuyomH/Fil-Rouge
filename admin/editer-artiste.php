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

/********************/
/* ÉDITER COLLECTIF */
/********************/
require_once('inc/edit-artiste.inc.php');
?>
<?php require_once('inc/head.inc.php'); ?>

<?php if(!isset($_SESSION['currentArt'])) { ?>
        <!-- étape 1 -->
        <h2>Étape 1</h2>

        <?php echo $warning; ?>

        <form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" class="single-line">
          <p>
          <select name="select-art" id="select-art">
            <option hidden value=\"\">Choisir un artiste</option>
            <option value=\"0\">-</option>
<?php echo $listArt; ?>
          </select>
          <input type="submit" value="Sélectionner">
          </p>
        </form>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" class="wide">
          <div class="box">
            <p><label for="nom">Nom artiste* :</label> <input type="text" id="nom" name="nom" maxlength="100" value="<?php echo $nomVal ?>"></p>
            <p><label for="nom">Prénom artiste :</label> <input type="text" id="prenom" name="prenom" maxlength="100" value="<?php echo $prenomVal ?>"></p>
          </div>
          <div class="box">
            <p><label for="bio">Bio artiste* :</label> <textarea id="bio" name="bio" maxlength="1000"><?php echo $bioVal ?></textarea></p>
            <p><label for="bio-en">Bio artiste anglais :</label> <textarea id="bio-en" name="bio-en" maxlength="1000"><?php echo $bioValEn ?></textarea></p>
            <p><label for="bio-zh">Bio artiste chinois :</label> <textarea id="bio-zh" name="bio-zh" maxlength="1000"><?php echo $bioValZh ?></textarea></p>
          </div>
          <p><label for="bind-coll">Lier à un collectif :</label>
          <select name="bind-coll" id="bind-coll">
            <option hidden value="">Choisir un collectif</option>
            <option value="0">-</option>
<?php echo $listColl; ?>
          </select>
          </p>
          <p>
            <input type="submit" name="art1" id="art1" value="<?php echo $submitVal ?>">
            <input type="submit" name="art1-reset" id="art1-reset" value="Annuler">
          </p>
        </div>
      </form>
<?php } else { ?>
        <!-- étape 2 -->
        <h2>Étape 2</h2>

        <?php if(isset($_SESSION['nomCurrentArt'])) { echo "<h3>► {$_SESSION['nomCurrentArt']}</h3>"; } ?>

        <?php echo $warning; ?>

        <form enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
          <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $size; ?>">
        <p>
          <input type="file" name="fichier" accept=".jpg">
          <input type="submit" name="upload" value="Envoyer le fichier" class="upload-submit">
        </p>
        </form>

        <?php
        if(file_exists("../artistes/".$_SESSION['currentArt'].".jpg"))
        {
          echo "<div class=\"upload-preview\">
          <img src=\"../artistes/{$_SESSION['currentArt']}.jpg\" alt=\"photo de l'artiste\">
          <form method=\"post\" action=\"{$_SERVER['PHP_SELF']}\">
            <input type=\"hidden\" name=\"del\" value=\"{$_SESSION['currentArt']}\">
            <button type=\"submit\"><i class=\"fas fa-trash-alt\"></i></button>
          </form>
        </div>\r\n";
        }
        ?>

        <form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" class="wide">
          <p><input type="submit" name="coll2-reset" id="coll1-reset" value="Terminer"></p>
        </form>
<?php } ?>

<?php require_once('inc/foot.inc.php'); ?>
