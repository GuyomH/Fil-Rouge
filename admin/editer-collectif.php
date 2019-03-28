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
require_once('inc/edit-coll.inc.php');
?>
<?php require_once('inc/head.inc.php'); ?>

<?php if(!isset($_SESSION['currentColl'])) { ?>
        <!-- étape 1 -->
        <h2>Étape 1</h2>

        <?php echo $warning; ?>

        <form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" class="single-line">
          <p>
          <select name="select-coll" id="select-coll">
            <option hidden value=\"\">Choisir un collectif</option>
            <option value=\"0\">-</option>
<?php echo $listColl; ?>
          </select>
          <input type="submit" value="Sélectionner">
          </p>
        </form>

        <form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" class="wide">
          <p><label for="nom">Nom collectif* :</label> <input type="text" id="nom" name="nom" maxlength="100" value="<?php echo $nomVal ?>"></p>
          <div class="box">
            <p><label for="info">Info collectif* :</label> <textarea id="info" name="info" maxlength="1000" ><?php echo $infoVal ?></textarea></p>
            <p><label for="info-en">Info collectif anglais :</label> <textarea id="info-en" name="info-en" maxlength="1000"><?php echo $infoValEn ?></textarea></p>
            <p><label for="info-zh">Info collectif chinois :</label> <textarea id="info-zh" name="info-zh" maxlength="1000"><?php echo $infoValZh ?></textarea></p>
          </div>
          <p>
            <input type="submit" name="coll1" id="coll1" value="<?php echo $submitVal ?>">
            <input type="submit" name="coll1-reset" id="coll1-reset" value="Annuler">
          </p>
        </form>
<?php } else { ?>
        <!-- étape 2 -->
        <h2>Étape 2</h2>

        <?php if(isset($_SESSION['nomCurrentColl'])) { echo "<h3>► {$_SESSION['nomCurrentColl']}</h3>"; } ?>

        <?php echo $warning; ?>

        <form enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
          <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $size; ?>">
          <p>
            <input type="file" name="fichier" accept=".jpg">
            <input type="submit" name="upload" value="Envoyer le fichier" class="upload-submit">
          </p>
        </form>

        <?php
        if(file_exists("../collectifs/".$_SESSION['currentColl'].".jpg"))
        {
          echo "<div class=\"upload-preview\">
          <img src=\"../collectifs/{$_SESSION['currentColl']}.jpg\" alt=\"photo du collectif\">
          <form method=\"post\" action=\"{$_SERVER['PHP_SELF']}\">
            <input type=\"hidden\" name=\"del\" value=\"{$_SESSION['currentColl']}\">
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
