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


// ÉTAPE 0
// TODO
// Si mode édition : récupération et chargement des valeurs dans les champs
// Voir le système de transmission de l'id de l'expo en mode édition/création
// test
$lastID = 6;

// ÉTAPE 1
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

// ÉTAPE 2
$empTab = "";
$tooBig = "";
$sql = "SELECT * FROM emplacements;";
$qry = $db->query($sql);
foreach ($qry as $val)
{
  $empTab .= "\t\t\t\t<tr><td><label for=\"emp{$val['num_emp']}\">{$val['num_emp']}</label></td><td><select name=\"emp{$val['num_emp']}\" id=\"emp{$val['num_emp']}\">\r\n\t\t\t\t\t<option hidden>Choisir</option>\r\n\t\t\t\t\t<option id=\"0\">-</option>\r\n";

    $sql2 = "SELECT num_emp, longueur_emp, largeur_emp, hauteur_emp
    FROM emplacements
    WHERE num_emp = '{$val['num_emp']}';";
    $qry2 = $db->query($sql2);
    $dimension = $qry2->fetch(); // 1 résultat attendu

    $sql3 = "SELECT titre_oeuvre, O.id_oeuvre
    FROM oeuvres AS O
    INNER JOIN avoir AS AV ON O.id_oeuvre = AV.id_oeuvre
    INNER JOIN types AS T ON AV.id_type = T.id_type
    INNER JOIN deux_dimensions AS DD ON AV.id_pic = DD.id_pic
    INNER JOIN trois_dimensions AS DDD ON AV.id_tri = DDD.id_tri
    WHERE longueur_tri <= {$dimension['longueur_emp']} AND largeur_tri <= {$dimension['largeur_emp']} AND hauteur_tri <= {$dimension['hauteur_emp']}
    AND longueur_pic <= {$dimension['longueur_emp']} AND hauteur_pic <= {$dimension['hauteur_emp']}
    ORDER BY titre_oeuvre;";
    $qry3 = $db->query($sql3);
    foreach ($qry3 as $ovr) {
      $empTab .= "\t\t\t\t\t<option id=\"{$ovr['id_oeuvre']}\">{$ovr['titre_oeuvre']}</option>\r\n";
    }

    $sql4 = "SELECT titre_oeuvre, O.id_oeuvre
    FROM oeuvres AS O
    INNER JOIN avoir AS AV ON O.id_oeuvre = AV.id_oeuvre
    INNER JOIN types AS T ON AV.id_type = T.id_type
    INNER JOIN deux_dimensions AS DD ON AV.id_pic = DD.id_pic
    INNER JOIN trois_dimensions AS DDD ON AV.id_tri = DDD.id_tri
    WHERE (cat_type <> '0D')
    AND (longueur_tri > {$dimension['longueur_emp']} OR largeur_tri > {$dimension['largeur_emp']} OR hauteur_tri > {$dimension['hauteur_emp']})
    OR (longueur_pic > {$dimension['longueur_emp']} OR hauteur_pic > {$dimension['hauteur_emp']})
    ORDER BY titre_oeuvre;";
    $qry4 = $db->query($sql4);
    foreach ($qry4 as $ovr2) {
      $tooBig .= "\t\t\t\t\t\t<option id=\"{$ovr2['id_oeuvre']}\" disabled>{$ovr2['titre_oeuvre']}</option>\r\n";
    }

    if(!empty($tooBig))
    {
      $empTab .= "\t\t\t\t\t<optgroup label=\"Ne loge pas dans l'emplacement\">\r\n";
      $empTab .= $tooBig;
      $empTab .= "\t\t\t\t\t</optgroup>\r\n";
      $tooBig = ""; // Reset de la variable
    }

  $empTab .= "\t\t\t\t</select></td><td><input type=\"date\" name=\"date{$val['num_emp']}\"></td><td></tr>\r\n";
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

          <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <table id="expo-emp">
              <tr><th>Emplacement</th><th>Titre de l'oeuvre</th><th>Date de livraison</th></tr>
<?php echo $empTab; ?>
            </table>
            <p><input type="submit" name="expo2" id="expo2" value="Valider étape 2"></p>
          </form>

<?php } ?>

<?php require_once('inc/foot.inc.php'); ?>
