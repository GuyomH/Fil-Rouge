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
// Var init
$warning = "";
$nomVal = "";
$infoVal = "";
$infoValEn = "";
$infoValZh = "";

// ÉTAPE 1
// Reset
if(isset($_POST['coll1-reset']))
{
  header('Location: editer-expo.php');
  exit();
}

// Traitement
if(isset($_POST['coll1']))
{
  // Traductions (non obligatoire)
  $info_en =
  isset($_POST['info-en']) && !empty($_POST['info-en']) && (strlen($_POST['info-en']) <= 1000)?trim($_POST['info-en']):NULL;
  $info_zh =
  isset($_POST['info-zh']) && !empty($_POST['info-zh']) && (strlen($_POST['info-zh']) <= 1000)?trim($_POST['info-zh']):NULL;

  // Données obligatoires
  if( isset($_POST['nom']) && !empty($_POST['nom']) && (strlen($_POST['nom']) <= 100) &&
  isset($_POST['info']) && !empty($_POST['info']) && (strlen($_POST['info']) <= 1000) )
  {
    // Variabilisation
    $nom = trim($_POST['nom']);
    $info = trim($_POST['info']);

    // Insertion des données en français
    $sql = "INSERT INTO collectifs(nom_col, info_col)
    VALUES(:nom, :info);";

    $qry = $db->prepare($sql);
    $qry->bindValue(':nom', $nom, PDO::PARAM_STR);
    $qry->bindValue(':info', $info, PDO::PARAM_STR);

    try
    {
      $qry->execute();
    }
    catch (Exception $e)
    {
      $warning = "<p class=\"warning\">Une erreur est survenue !</p>";
    }

    // Récupération de l'ID et du nom
    $_SESSION['currentColl'] = $db->lastInsertId();
    $_SESSION['nomCurrentColl'] = $nom;

    // Insertion des données en anglais et en chinois
    $sql2 = "INSERT INTO collectifs_trad(info_col_trad, id_langue, id_col)
    VALUES(:info_en, 1, :id_col), (:info_zh, 2, :id_col);";
    $qry2 = $db->prepare($sql2);
    $qry2->bindValue(':info_en', $info_en, PDO::PARAM_STR);
    $qry2->bindValue(':info_zh', $info_zh, PDO::PARAM_STR);
    $qry2->bindValue(':id_col', $_SESSION['currentColl'], PDO::PARAM_INT);

    try
    {
      $qry2->execute();
      header('Location: editer-collectif.php');
      exit();
    }
    catch (Exception $e)
    {
      // Traductions facultatives
      header('Location: editer-collectif.php');
      exit();
    }
  } else {
    $warning = "<p class=\"warning\">Les champs obligatoires ne sont pas correctement remplis !</p>";
  }
}
?>
<?php require_once('inc/head.inc.php'); ?>

<?php if(!isset($_SESSION['currentColl'])) { ?>
        <!-- étape 1 -->
        <h2>Étape 1</h2>

        <?php echo $warning; ?>

        <form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" class="wide">
          <p><label for="nom">Nom collectif* :</label> <input type="text" id="nom" name="nom" maxlength="100" value="<?php echo $nomVal ?>"></p>
          <p><label for="info">Info collectif* :</label> <textarea id="info" name="info" maxlength="1000"><?php echo $infoVal ?></textarea></p>
          <p><label for="info-en">Info collectif anglais :</label> <textarea id="info-en" name="info-en" maxlength="1000"><?php echo $infoValEn ?></textarea></p>
          <p><label for="info-zh">Info collectif chinois :</label> <textarea id="info-zh" name="info-zh" maxlength="1000"><?php echo $infoValZh ?></textarea></p>
          <p>
            <input type="submit" name="coll1" id="coll1" value="Valider étape 1">
            <input type="submit" name="coll1-reset" id="coll1-reset" value="Annuler">
          </p>
        </form>

<?php } else { ?>
        <!-- étape 2 -->
        <h2>Étape 2</h2>

        <p>Insertion média.</p>

<?php } ?>

<?php require_once('inc/foot.inc.php'); ?>
