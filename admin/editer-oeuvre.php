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
require_once('inc/edit-oeuvre.inc.php');
?>
<?php require_once('inc/head.inc.php'); ?>

<?php if(!isset($_SESSION['currentOvr'])) { ?>
        <!-- étape 1 -->
        <h2>Étape 1</h2>

        <?php echo $warning; ?>

        <form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" class="single-line">
          <p>
          <select name="select-ovr" id="select-ovr">
            <option hidden value=\"\">Choisir une oeuvre</option>
            <option value=\"0\">-</option>
<?php echo $listOvr; ?>
          </select>
          <input type="submit" value="Sélectionner">
          </p>
        </form>

        <form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" class="wide">
          <p><label for="titre">Titre oeuvre* :</label> <input type="text" id="titre" name="titre" maxlength="500" value="<?php echo $titreVal ?>"></p>

          <p><label for="desc">Descriptif oeuvre* :</label> <textarea id="desc" name="desc" maxlength="1000"><?php echo $descVal ?></textarea></p>

          <p><label for="an">Année oeuvre* :</label> <input type="text" id="an" name="an" maxlength="4" pattern="^\d{4}$" value="<?php echo $anVal ?>"></p>

          <p><label for="type-ovr">Type oeuvre* :</label>
            <select name="type-ovr" id="type-ovr">
              <option hidden value="">Choisir un type</option>
<?php echo $listType; ?>
            </select>
          </p>

          <p><label for="long">Longueur (en cm) :</label> <input type="text" id="long" name="long" maxlength="8" pattern="^[0-9]{0,3}(\.)?[0-9]{0,2}$" value="<?php echo $longVal ?>"></p>
          <p><label for="larg">Largeur (en cm) :</label> <input type="text" id="larg" name="larg" maxlength="8" pattern="^[0-9]{0,3}(\.)?[0-9]{0,2}$" value="<?php echo $largVal ?>"></p>
          <p><label for="haut">Hauteur (en cm) :</label> <input type="text" id="haut" name="haut" maxlength="8" pattern="^[0-9]{0,3}(\.)?[0-9]{0,2}$" value="<?php echo $hautVal ?>"></p>

          <p><label for="bind-art">Lier à un artiste :</label>
          <select name="bind-art" id="bind-art">
            <option hidden value="">Choisir un artiste</option>
            <option value="0">-</option>
<?php echo $listArt; ?>
          </select>
          </p>

          <p><label for="titre-en">Titre oeuvre anglais :</label> <input type="text" id="titre_en" name="titre-en" maxlength="500" value="<?php echo $titreValEn ?>"></p>
          <p><label for="desc-en">Descriptif oeuvre anglais :</label> <textarea id="desc-en" name="desc-en" maxlength="1000"><?php echo $descValEn ?></textarea></p>
          <p><label for="titre-zh">Titre oeuvre chinois :</label> <input type="text" id="titre_zh" name="titre-zh" maxlength="500" value="<?php echo $titreValZh ?>"></p>
          <p><label for="desc-zh">Descriptif oeuvre chinois :</label> <textarea id="desc-zh" name="desc-zh" maxlength="1000"><?php echo $descValZh ?></textarea></p>
          <p>
            <input type="submit" name="ovr1" id="ovr1" value="<?php echo $submitVal ?>">
            <input type="submit" name="ovr1-reset" id="ovr1-reset" value="Annuler">
          </p>
        </form>

<?php } else { ?>

        <h2>Étape 2</h2>

        <?php if(isset($_SESSION['nomCurrentOvr'])) { echo "<h3>► {$_SESSION['nomCurrentOvr']}</h3>"; } ?>

        <?php echo $warning; ?>

        <!-- <form enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
          <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $size; ?>">
        <p>
          <input type="file" name="fichier" accept=".jpg">
          <input type="submit" name="upload" value="Envoyer le fichier" class="upload-submit">
        </p>
        </form> -->

        <!-- <?php
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
        ?> -->

        <form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" class="wide">
          <p><input type="submit" name="coll2-reset" id="coll1-reset" value="Terminer"></p>
        </form>
<?php } ?>

<?php require_once('inc/foot.inc.php'); ?>
