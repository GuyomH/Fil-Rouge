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
// var init
$warning = "";

// Étape 0
// TODO
// Si mode édition : récupération et chargement des valeurs dans les champs
// Voir le système de transmission de l'id de l'expo en mode édition/création

// Étape 1
if(isset($_POST['expo1']))
{
  // Traductions (non obligatoire)
  $titre_en =
  isset($_POST['titre-en']) && !empty($_POST['titre-en']) && (strlen($_POST['titre-en']) <= 500)?trim($_POST['titre-en']):NULL;
  $titre_zh =
  isset($_POST['titre-zh']) && !empty($_POST['titre-zh']) && (strlen($_POST['titre-zh']) <= 500)?trim($_POST['titre-zh']):NULL;
  $desc_en =
  isset($_POST['desc-en']) && !empty($_POST['desc-en']) && (strlen($_POST['desc-en']) <= 1000)?trim($_POST['desc-en']):NULL;
  $desc_zh =
  isset($_POST['desc-zh']) && !empty($_POST['desc-zh']) && (strlen($_POST['desc-zh']) <= 1000)?trim($_POST['desc-zh']):NULL;

  // Éléments obligatoires
  if ( isset($_POST['titre']) && !empty($_POST['titre']) && (strlen($_POST['titre']) <= 500) &&
  isset($_POST['desc']) && !empty($_POST['desc']) && (strlen($_POST['desc']) <= 1000) &&
  isset($_POST['debut']) && dateChecker($_POST['debut']) &&
  isset($_POST['fin']) && dateChecker($_POST['fin']) )
  {
    // Variabilisation
    $titre = trim($_POST['titre']);
    $desc = trim($_POST['desc']);
    $debut = $_POST['debut'];
    $fin = $_POST['fin'];

    // Insertion des données en français
    $sql = "INSERT INTO expositions(titre_expo, descriptif_expo, debut_expo, fin_expo)
    VALUES(:titre, :desc, :debut, :fin);";
    $qry = $db->prepare($sql);
    $qry->bindValue(':titre', $titre, PDO::PARAM_STR);
    $qry->bindValue(':desc', $desc, PDO::PARAM_STR);
    $qry->bindValue(':debut', $debut, PDO::PARAM_STR);
    $qry->bindValue(':fin', $fin, PDO::PARAM_STR);
    try
    {
      $qry->execute();
    }
    catch (Exception $e)
    {
      $warning .= "<p>Erreur : " . $e->getMessage() . "</hp>";
    }
    // Récupération de l'ID
    $lastID = $db->lastInsertId();
    // Insertion des données en anglais et en chinois
    $sql2 = "INSERT INTO expositions_trad(titre_expo_trad, descriptif_expo_trad, id_langue, id_expo)
    VALUES(:titre_en, :desc_en, 1, :id_expo), (:titre_zh, :desc_zh, 2, :id_expo);";
    $qry2 = $db->prepare($sql2);
    $qry2->bindValue(':titre_en', $titre_en, PDO::PARAM_STR);
    $qry2->bindValue(':titre_zh', $titre_zh, PDO::PARAM_STR);
    $qry2->bindValue(':desc_en', $desc_en, PDO::PARAM_STR);
    $qry2->bindValue(':desc_zh', $desc_zh, PDO::PARAM_STR);
    $qry2->bindValue(':id_expo', $lastID, PDO::PARAM_INT);
    try
    {
      $qry2->execute();
    }
    catch (Exception $e)
    {
      $warning .= "<p>Erreur : " . $e->getMessage() . "</hp>";
    }
  } else {
    $warning = "<p class=\"warning\">Les champs obligatoires ne sont pas correctement remplis !</p>";
  }
}
?>
<?php require_once('inc/head.inc.php'); ?>

<?php if(!isset($lastID) || $lastID == 0){ ?>
          <!-- étape 1 -->
          <h2>Étape 1</h2>

          <?php echo $warning; ?>

          <form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" class="wide">
            <p><label for="titre">Titre* :</label> <input type="text" id="titre" name="titre" maxlength="500"></p>
            <p><label for="desc">Descriptif* :</label> <textarea id="desc" name="desc" maxlength="1000"></textarea></p>

            <p><label for="titre-en">Titre anglais :</label> <input type="text" id="titre-en" name="titre-en" maxlength="500"></p>
            <p><label for="desc-en">Descriptif anglais :</label> <textarea id="desc-en" name="desc-en" maxlength="1000"></textarea></p>

            <p><label for="titre-zh">Titre chinois :</label> <input type="text" id="titre-zh" name="titre-zh" maxlength="500"></p>
            <p><label for="desc">Descriptif chinois :</label> <textarea id="desc-zh" name="desc-zh" maxlength="1000"></textarea></p>

            <p><label for="debut">Date début* :</label> <input type="date" id="debut" name="debut"></p>
            <p><label for="fin">Date fin* :</label> <input type="date" id="fin" name="fin"></p>

            <p><input type="submit" name="expo1" id="expo1" value="Valider étape 1"></p>
          </form>

<?php } else { ?>
          <!-- étape 2 -->
          <h2>Étape 2</h2>
          <!--
          <table>
            <tr><th>Emplacement</th><th>Titre</th><th>Date de livraison</th><th colspan="2">&nbsp;</th></tr>
            <tr><td>1-01</td><td><input type="text" name="titre-1-01" maxlength="500"></td><td><input type="date" name="date-1-01"></td><td><input type="submit" name="emp1-01" value="Enregistrer"><td><td><input type="submit" name="dele1-01" value="Supprimer"></td></tr>
          </table>
          -->
<?php } ?>

<?php require_once('inc/foot.inc.php'); ?>
