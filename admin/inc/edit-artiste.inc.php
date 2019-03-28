<?php
// Var init
$warning = "";
$nomVal = "";
$prenomVal = "";
$bioVal = "";
$bioValEn = "";
$bioValZh = "";
$listArt = "";
$listColl = "";
$submitVal = "Valider étape 1";

// ÉTAPE 0
// Liste des artistes existants
$sql0 = "SELECT id_art, nom_art, prenom_art
FROM artistes
WHERE nom_art IS NOT NULL
ORDER BY nom_art;";
$qry0 = $db->query($sql0);
foreach ($qry0 as $art)
{
  $artist = isset($art['prenom_art']) ? $art['nom_art'] . " " . $art['prenom_art'] : $art['nom_art'];
  $listArt .= "\t\t\t<option value=\"{$art['id_art']}\">{$artist}</option>\r\n";
}

// Traitement du listing
if(isset($_POST['select-art']) && intval($_POST['select-art']) > 0)
{
  $idArt = intval($_POST['select-art']);

  $sqlArt = "SELECT * FROM artistes
  WHERE id_art = {$idArt};";

  $sqlArtTrad = "SELECT L.code_langue, bio_art_trad
  FROM artistes_trad AS AT
  INNER JOIN langues AS L ON AT.id_langue = L.id_langue
  INNER JOIN artistes AS A ON AT.id_art = A.id_art
  WHERE A.id_art = {$idArt};";

  $qryArt = $db->query($sqlArt);
  $getArt = $qryArt->fetch();

  // Si l'artiste n'existe pas, on recharge la page
  if(empty($getArt['id_art']))
  {
    header('Location: editer-artiste.php');
    exit();
  }

  // Si l'artiste existe
  $nomVal = $getArt['nom_art'];
  $prenomVal = $getArt['prenom_art'];
  $bioVal = $getArt['bio_art'];
  $idCollVal = $getArt['id_col'];

  $qryArtTrad = $db->query($sqlArtTrad);
  foreach ($qryArtTrad as $trad)
  {
    // On reconsitue dynamiquement le nom des variables
    $varLang = ucFirst($trad['code_langue']); // En ou Zh
    ${'bioVal'.$varLang} = $trad['bio_art_trad'];
  }

  // Variable de session
  $artist = isset($getArt['prenom_art']) ? $getArt['prenom_art'] . " " . $getArt['nom_art'] : $getArt['nom_art'];
  $_SESSION['loadedArt'] = $idArt;
  $_SESSION['loadedArtColl'] = $idCollVal;
  $submitVal = "Mettre à jour ".$artist;
}

// ÉTAPE 0 bis
// Liste des collectifs
$sql0b = "SELECT id_col,nom_col
FROM collectifs
WHERE nom_col IS NOT NULL
ORDER BY nom_col;";
$qry0b = $db->query($sql0b);
foreach ($qry0b as $coll)
{
  if(isset($_SESSION['loadedArtColl']) &&  ($_SESSION['loadedArtColl'] == $coll['id_col']))
  {
    $selectedColl = " selected";
  } else {
    $selectedColl = "";
  }
  $listColl .= "\t\t\t<option value=\"{$coll['id_col']}\"{$selectedColl}>{$coll['nom_col']}</option>\r\n";
}

// ÉTAPE 1
// Auto reset
if(isset($_SESSION['loadedArt']) && !isset($_SESSION['currentArt']))
{
  unset($_SESSION['loadedArt']);
  unset($_SESSION['loadedArtColl']);
}

// Reset
if(isset($_POST['art1-reset']))
{
  unset($_SESSION['loadedArt']);
  unset($_SESSION['loadedArtColl']);
  header('Location: editer-artiste.php');
  exit();
}

// Traitement
if(isset($_POST['art1']))
{
  // Traductions (non obligatoire)
  $bio_en =
  isset($_POST['bio-en']) && !empty($_POST['bio-en']) && (strlen($_POST['bio-en']) <= 1000)?trim($_POST['bio-en']):NULL;
  $bio_zh =
  isset($_POST['bio-zh']) && !empty($_POST['bio-zh']) && (strlen($_POST['bio-zh']) <= 1000)?trim($_POST['bio-zh']):NULL;
  $prenom =
  isset($_POST['prenom']) && !empty($_POST['prenom']) && (strlen($_POST['prenom']) <= 100)?ucWords(mb_strToLower(trim($_POST['prenom']))):NULL;
  $collectif =
  isset($_POST['bind-coll']) && (intval($_POST['bind-coll']) > 0)?intval($_POST['bind-coll']):1; // Valeur par défaut des artistes sans collectif

  // Données obligatoires
  if( isset($_POST['nom']) && !empty($_POST['nom']) && (strlen($_POST['nom']) <= 100) &&
  isset($_POST['bio']) && !empty($_POST['bio']) && (strlen($_POST['bio']) <= 1000) )
  {
    // Variabilisation
    $nom = mb_strToUpper(trim($_POST['nom']));
    $bio = trim($_POST['bio']);

    // Insertion des données en français
    if(isset($_SESSION['loadedArt']))
    {
      $sql = "UPDATE artistes
      SET nom_art = :nom,
      prenom_art = :prenom,
      bio_art = :bio,
      id_col = :idcol
      WHERE id_art = {$_SESSION['loadedArt']};";
    } else {
      $sql = "INSERT INTO artistes(nom_art, prenom_art, bio_art, id_col)
      VALUES(:nom, :prenom, :bio, :idcol);";
    }

    $qry = $db->prepare($sql);
    $qry->bindValue(':nom', $nom, PDO::PARAM_STR);
    $qry->bindValue(':prenom', $prenom, PDO::PARAM_STR);
    $qry->bindValue(':bio', $bio, PDO::PARAM_STR);
    $qry->bindValue(':idcol', $collectif, PDO::PARAM_INT);

    try
    {
      $qry->execute();
    }
    catch (Exception $e)
    {
      $warning = "<p class=\"warning\">Une erreur est survenue !</p>";
    }

    // Récupération de l'ID et du nom
    $_SESSION['currentArt'] = isset($_SESSION['loadedArt']) ? $_SESSION['loadedArt'] : $db->lastInsertId();
    $_SESSION['nomCurrentArt'] = !is_null($prenom) ? $prenom." ".$nom : $nom;

    // Insertion des données en anglais et en chinois avec prise en compte de la MAJ
    $sql2 = "INSERT INTO artistes_trad(bio_art_trad, id_langue, id_art)
    VALUES(:bio_en, 1, :id_art), (:bio_zh, 2, :id_art)
    ON DUPLICATE KEY UPDATE bio_art_trad = values(bio_art_trad);";

    $qry2 = $db->prepare($sql2);
    $qry2->bindValue(':bio_en', $bio_en, PDO::PARAM_STR);
    $qry2->bindValue(':bio_zh', $bio_zh, PDO::PARAM_STR);
    $qry2->bindValue(':id_art', $_SESSION['currentArt'], PDO::PARAM_INT);

    try
    {
      $qry2->execute();
      header('Location: editer-artiste.php');
      exit();
    }
    catch (Exception $e)
    {
      // Traductions facultatives
      header('Location: editer-artiste.php');
      exit();
    }
  } else {
    unset($_SESSION['loadedArt']);
    unset($_SESSION['loadedArtColl']);
    unset($_SESSION['currentArt']);
    unset($_SESSION['nomCurrentArt']);
    $warning = "<p class=\"warning\">Les champs obligatoires ne sont pas correctement remplis !</p>";
  }
}

// ÉTAPE 2
// Reset
if(isset($_POST['coll2-reset']))
{
  // Destruction des variables de session
  unset($_SESSION['loadedArt']);
  unset($_SESSION['loadedArtColl']);
  unset($_SESSION['currentArt']);
  unset($_SESSION['nomCurrentArt']);
  header('Location: editer-artiste.php');
  exit();
}

// Delete média (type unique : jpg)
if(isset($_POST['del']) && intval($_POST['del']) > 0)
{
    $path = substr_replace((__dir__), "", -10);
    unlink($path."/artistes/".$_POST['del'].".jpg");
    $warning = "<p class=\"warning\">Supression du fichier réussie !</p>";
}

// Fichier de traitement de l'upload
require_once('inc/upload-art.inc.php');
?>
