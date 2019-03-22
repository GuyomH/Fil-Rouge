<?php
// Var init
$warning = "";
$nomVal = "";
$infoVal = "";
$infoValEn = "";
$infoValZh = "";
$listColl = "";
$submitVal = "Valider étape 1";

// ÉTAPE 0
// Liste des collectifs existants
$sql0 = "SELECT id_col, nom_col
FROM collectifs
ORDER BY nom_col;";
$qry0 = $db->query($sql0);
foreach ($qry0 as $coll)
{
  $listColl .= "\t\t\t<option value=\"{$coll['id_col']}\">{$coll['nom_col']}</option>\r\n";
}

// Traitement du listing
if(isset($_POST['select-coll']) && intval($_POST['select-coll']) > 0)
{
  $idColl = intval($_POST['select-coll']);

  $sqlColl = "SELECT * FROM collectifs
  WHERE id_col = {$idColl};";

  $sqlCollTrad = "SELECT L.code_langue, info_col_trad
  FROM collectifs_trad AS CT
  INNER JOIN langues AS L ON CT.id_langue = L.id_langue
  INNER JOIN collectifs AS C ON CT.id_col = C.id_col
  WHERE C.id_col = {$idColl};";

  $qryColl = $db->query($sqlColl);
  $getColl = $qryColl->fetch();
  // Si le collectif n'existe pas, on recharge la page
  if(empty($getColl['id_col']))
  {
    header('Location: editer-collectif.php');
    exit();
  }
  // Si le collectif existe
  $nomVal = $getColl['nom_col'];
  $infoVal = $getColl['info_col'];

  $qryCollTrad = $db->query($sqlCollTrad);
  foreach ($qryCollTrad as $trad)
  {
    // On reconsitue dynamiquement le nom des variables
    $varLang = ucFirst($trad['code_langue']); // En ou Zh
    ${'infoVal'.$varLang} = $trad['info_col_trad'];
  }
  // Variable de session
  $_SESSION['loadedColl'] = $idColl;
  $submitVal = "Mettre à jour ".$nomVal;
}

// ÉTAPE 1
// Reset
if(isset($_POST['coll1-reset']))
{
  unset($_SESSION['loadedColl']);
  header('Location: editer-collectif.php');
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
    if(isset($_SESSION['loadedColl']))
    {
      $sql = "UPDATE collectifs
      SET nom_col = :nom,
      info_col = :info
      WHERE id_col = {$_SESSION['loadedColl']};";
    } else {
      $sql = "INSERT INTO collectifs(nom_col, info_col)
      VALUES(:nom, :info);";
    }

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
    $_SESSION['currentColl'] = isset($_SESSION['loadedColl']) ? $_SESSION['loadedColl'] : $db->lastInsertId();
    $_SESSION['nomCurrentColl'] = $nom;

    // Insertion des données en anglais et en chinois avec prise en compte de la MAJ
    $sql2 = "INSERT INTO collectifs_trad(info_col_trad, id_langue, id_col)
    VALUES(:info_en, 1, :id_col), (:info_zh, 2, :id_col)
    ON DUPLICATE KEY UPDATE info_col_trad = values(info_col_trad);";

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

// ÉTAPE 2
// Reset
if(isset($_POST['coll2-reset']))
{
  // Destruction des variables de session
  unset($_SESSION['currentColl']);
  unset($_SESSION['nomCurrentColl']);
  header('Location: editer-collectif.php');
  exit();
}

// Fichier de traitement de l'upload
require_once('inc/upload-coll.inc.php');
?>
