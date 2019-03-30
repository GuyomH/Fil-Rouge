<?php
// Var init
$warning = "";
$titreVal = "";
$titreValEn = "";
$titreValZh = "";
$descVal = "";
$descValEn = "";
$descValZh = "";
$listOvr = "";
$listArt = "";
$listType = "";
$longVal = "";
$largVal = "";
$hautVal = "";
$anVal = "";
$submitVal = "Valider étape 1";

// ÉTAPE 0
// Liste des collectifs existants
$sql0 = "SELECT id_oeuvre, titre_oeuvre
FROM oeuvres
ORDER BY titre_oeuvre;";
$qry0 = $db->query($sql0);
foreach ($qry0 as $ovr)
{
  $listOvr .= "\t\t\t<option value=\"{$ovr['id_oeuvre']}\">{$ovr['titre_oeuvre']}</option>\r\n";
}

// Traitement du listing
if(isset($_POST['select-ovr']) && intval($_POST['select-ovr']) > 0)
{
  $idOvr = intval($_POST['select-ovr']);

  $sqlOvr = "SELECT O.*, T.*, DD.*, DDD.*
  FROM oeuvres AS O
  INNER JOIN avoir AS A ON O.id_oeuvre = A.id_oeuvre
  INNER JOIN types AS T ON A.id_type = T.id_type
  INNER JOIN deux_dimensions AS DD ON A.id_pic = DD.id_pic
  INNER JOIN trois_dimensions AS DDD ON A.id_tri = DDD.id_tri
  WHERE O.id_oeuvre = {$idOvr};";

  $sqlOvrTrad = "SELECT L.code_langue, titre_oeuvre_trad, descriptif_oeuvre_trad
  FROM oeuvres_trad AS OT
  INNER JOIN langues AS L ON OT.id_langue = L.id_langue
  INNER JOIN oeuvres AS O ON OT.id_oeuvre = O.id_oeuvre
  WHERE O.id_oeuvre = {$idOvr};";

  $qryOvr = $db->query($sqlOvr);
  $getOvr = $qryOvr->fetch();

  // Si l'oeuvre n'existe pas, on recharge la page
  if(empty($getOvr['id_oeuvre']))
  {
    header('Location: editer-oeuvre.php');
    exit();
  }

  // Si l'oeuvre existe
  // On détermine le type de l'oeuvres
  switch ($getOvr['cat_type'])
  {
    case '2D':
      $idDD = $getOvr['id_pic'];
      $idDDD = "";
      $longVal = $getOvr['longueur_pic'];
      $hautVal = $getOvr['hauteur_pic'];
      break;
    case '3D':
      $idDDD = $getOvr['id_tri'];
      $idDD = "";
      $longVal = $getOvr['longueur_tri'];
      $largVal = $getOvr['largeur_tri'];
      $hautVal = $getOvr['hauteur_tri'];
      break;
    default:
      $idDD = "";
      $idDDD = "";
      break;
  }

  $titreVal = $getOvr['titre_oeuvre'];
  $descVal = $getOvr['descriptif_oeuvre'];
  $anVal = $getOvr['annee_oeuvre'];
  $typeOvrVal = $getOvr['id_type'];
  $idArtVal = $getOvr['id_art'];

  $qryOvrTrad = $db->query($sqlOvrTrad);
  foreach ($qryOvrTrad as $trad)
  {
    // On reconsitue dynamiquement le nom des variables
    $varLang = ucFirst($trad['code_langue']); // En ou Zh
    ${'titreVal'.$varLang} = $trad['titre_oeuvre_trad'];
    ${'descVal'.$varLang} = $trad['descriptif_oeuvre_trad'];
  }

  // Variable de session
  $_SESSION['loadedOvr'] = $idOvr; // id de l'oeuvre
  $_SESSION['loadedOvrArt'] = $idArtVal; // id de l'artiste lié à l'oeuvre
  $_SESSION['loadedOvrCat'] = $getOvr['cat_type']; //  catégorie de l'oeuvre
  $_SESSION['loadedOvrLib'] = $getOvr['libelle_type']; //  libellé de l'oeuvre
  $_SESSION['loadedOvr2D'] = $idDD; // id 2D
  $_SESSION['loadedOvr3D'] = $idDDD; // id 3D
  $submitVal = "Mettre à jour ".$titreVal; // Nom de l'oeuvre
}

// ÉTAPE 0 bis
// Liste des types
$sql0b = "SELECT id_type, libelle_type, cat_type
FROM types
ORDER BY libelle_type;";
$qry0b = $db->query($sql0b);
foreach ($qry0b as $typ)
{
  if(isset($typeOvrVal) && ($typeOvrVal == $typ['id_type']))
  {
    $selectedTyp = " selected";
  } else {
    $selectedTyp = "";
  }
  $listType .= "\t\t\t<option value=\"{$typ['id_type']}|{$typ['cat_type']}|{$typ['libelle_type']}\"{$selectedTyp}>{$typ['libelle_type']}</option>\r\n";
}

// ÉTAPE 0 ter
// Liste des artistes
$sql0t = "SELECT id_art, nom_art, prenom_art
FROM artistes
WHERE nom_art IS NOT NULL
ORDER BY nom_art;";
$qry0t = $db->query($sql0t);
foreach ($qry0t as $art)
{
  if(isset($idArtVal) && ($idArtVal == $art['id_art']))
  {
    $selectedArt = " selected";
  } else {
    $selectedArt = "";
  }
  $artist = isset($art['prenom_art']) ? $art['nom_art'] . " " . $art['prenom_art'] : $art['nom_art'];
  $listArt .= "\t\t\t<option value=\"{$art['id_art']}\"{$selectedArt}>{$artist}</option>\r\n";
}

// Reset
if(isset($_POST['ovr1-reset']))
{
  unset($_SESSION['loadedOvr']);
  unset($_SESSION['loadedOvrArt']);
  unset($_SESSION['loadedOvr2D']);
  unset($_SESSION['loadedOvr3D']);
  unset($_SESSION['loadedOvrCat']);
  unset($_SESSION['loadedOvrLib']);
  header('Location: editer-oeuvre.php');
  exit();
}

// Traitement
if(isset($_POST['ovr1']))
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
  $artiste =
  isset($_POST['bind-art']) && (intval($_POST['bind-art']) > 0)?intval($_POST['bind-art']):NULL;
  $longueur =
  isset($_POST['long']) && (floatval($_POST['long']) > 0)?intval($_POST['long']):0;
  $largeur =
  isset($_POST['larg']) && (floatval($_POST['larg']) > 0)?intval($_POST['larg']):0;
  $hauteur =
  isset($_POST['haut']) && (floatval($_POST['haut']) > 0)?intval($_POST['haut']):0;

  // Données obligatoires
  if( isset($_POST['titre']) && !empty($_POST['titre']) && (strlen($_POST['titre']) <= 500) &&
  isset($_POST['desc']) && !empty($_POST['desc']) && (strlen($_POST['desc']) <= 1000) &&
  isset($_POST['an']) && (strlen(intval($_POST['an'])) == 4) &&
  isset($_POST['type-ovr']) && !empty($_POST['type-ovr']) )
  {
    // Variabilisation
    $titre = ucFirst(trim($_POST['titre']));
    $desc = trim($_POST['desc']);
    $an = intval($_POST['an']);
    $tdata = explode("|", $_POST['type-ovr']);
    $idType = $tdata[0];
    $catType = $tdata[1];
    $libType = $tdata[2];

    // Insertion des données en français
    if(isset($_SESSION['loadedOvr']))
    {
      // Update
      $sql = "UPDATE oeuvres
      SET titre_oeuvre = :titre,
      descriptif_oeuvre = :desc,
      annee_oeuvre = :an,
      id_art = :idart
      WHERE id_oeuvre = {$_SESSION['loadedOvr']};";
    } else {
      // Insert
      $sql = "INSERT INTO oeuvres(titre_oeuvre, descriptif_oeuvre, annee_oeuvre, id_art)
      VALUES(:titre, :desc, :an, :idart);";
    }

    $qry = $db->prepare($sql);
    $qry->bindValue(':titre', $titre, PDO::PARAM_STR);
    $qry->bindValue(':desc', $desc, PDO::PARAM_STR);
    $qry->bindValue(':an', $an, PDO::PARAM_STR);
    $qry->bindValue(':idart', $artiste, PDO::PARAM_INT);

    try
    {
      $qry->execute();
    }
    catch (Exception $e)
    {
      $warning = "<p class=\"warning\">Une erreur est survenue !</p>";
      //$warning = "<p class=\"warning\">$e</p>";
    }

    // Récupération de l'ID et du titre
    $lastID = $db->lastInsertId();
    $_SESSION['currentOvr'] = isset($_SESSION['loadedOvr']) ? $_SESSION['loadedOvr'] : $lastID;
    $_SESSION['nomCurrentOvr'] = $titre;

    /**********/
    /* UPDATE */
    /**********/
    if(isset($_SESSION['loadedOvr']))
    {
      // Changement de libellé avec changement de type
      // On insère les nouvelles dimensions et on met à jour l'id_type, l'id_pic et l'id_tri dans la table avoir
      if($_SESSION['loadedOvrCat'] != $catType && $_SESSION['loadedOvrLib'] != $libType)
      {
        switch ($catType)
        {
          case '2D':
            $sql2 = "INSERT INTO deux_dimensions(longueur_pic, hauteur_pic)
                    VALUES(:longueur, :hauteur);";
            $qry2 = $db->prepare($sql2);
            $qry2->bindValue(':longueur', $longueur, PDO::PARAM_STR);
            $qry2->bindValue(':hauteur', $hauteur, PDO::PARAM_STR);
            try { $qry2->execute(); }
            catch (Exception $e) { //$warning = "<p class=\"warning\">Une erreur est survenue !</p>";
            $warning = "<p class=\"warning\">$e</p>"; }
            $lastID2 = $db->lastInsertId();
            $sql3 = "UPDATE avoir
            SET id_pic = :pic,
            id_tri = 1,
            id_type = :type
            WHERE id_oeuvre = {$_SESSION['loadedOvr']};";
            $qry3 = $db->prepare($sql3);
            $qry3->bindValue(':pic', $lastID2, PDO::PARAM_INT);
            $qry3->bindValue(':type', $idType, PDO::PARAM_INT);
            try { $qry3->execute(); }
            catch (Exception $e) { $warning = "<p class=\"warning\">Une erreur est survenue !</p>"; }
            break;
          case '3D':
            $sql2 = "INSERT INTO trois_dimensions(longueur_tri, largeur_tri, hauteur_tri)
                    VALUES(:longueur, :largeur, :hauteur);";
            $qry2 = $db->prepare($sql2);
            $qry2->bindValue(':longueur', $longueur, PDO::PARAM_STR);
            $qry2->bindValue(':hauteur', $hauteur, PDO::PARAM_STR);
            $qry2->bindValue(':largeur', $largeur, PDO::PARAM_STR);
            try { $qry2->execute(); }
            catch (Exception $e) { $warning = "<p class=\"warning\">Une erreur est survenue !</p>"; }
            $lastID2 = $db->lastInsertId();
            $sql3 = "UPDATE avoir
            SET id_pic = 1,
            id_tri = :tri,
            id_type = :type
            WHERE id_oeuvre = {$_SESSION['loadedOvr']};";
            $qry3 = $db->prepare($sql3);
            $qry3->bindValue(':tri', $lastID2, PDO::PARAM_INT);
            $qry3->bindValue(':type', $idType, PDO::PARAM_INT);
            try { $qry3->execute(); }
            catch (Exception $e) { $warning = "<p class=\"warning\">Une erreur est survenue !</p>"; }
            break;
          default: // 0D
            $sql3 = "UPDATE avoir
            SET id_pic = 1,
            id_tri = 1,
            id_type = :type
            WHERE id_oeuvre = {$_SESSION['loadedOvr']};";
            $qry3 = $db->prepare($sql3);
            $qry3->bindValue(':type', $idType, PDO::PARAM_INT);
            try { $qry3->execute(); }
            catch (Exception $e) { $warning = "<p class=\"warning\">Une erreur est survenue !</p>"; }
            break;
        }
      } else {
        // Changement de libellé sans changement de type
        // On met à jour l'id_type dans la table avoir
        if($_SESSION['loadedOvrCat'] == $catType && $_SESSION['loadedOvrLib'] != $libType)
        {
          $sqlUp1 = "UPDATE avoir
          SET id_type = :type
          WHERE id_oeuvre = {$_SESSION['loadedOvr']}";
          $qryUp1 = $db->prepare($sqlUp1);
          $qryUp1->bindValue(':type', $idType, PDO::PARAM_INT);
          $qryUp1->execute();
        }
        // Gestion des dimensions
        switch ($catType)
        {
          case '2D':
            if(!empty($_SESSION['loadedOvr2D']))
            {
              $sql2 = "UPDATE deux_dimensions
              SET longueur_pic = :longueur,
              hauteur_pic = :hauteur
              WHERE id_pic = {$_SESSION['loadedOvr2D']};";
            } else {
              $sql2 = "INSERT INTO deux_dimensions(longueur_pic, hauteur_pic)
                      VALUES(:longueur, :hauteur);";
            }
            $qry2 = $db->prepare($sql2);
            $qry2->bindValue(':longueur', $longueur, PDO::PARAM_STR);
            $qry2->bindValue(':hauteur', $hauteur, PDO::PARAM_STR);
            try { $qry2->execute(); }
            catch (Exception $e) { $warning = "<p class=\"warning\">Une erreur est survenue !</p>"; }
            $lastID2 = $db->lastInsertId();
            if(!empty($_SESSION['loadedOvr2D']))
            {
              $sql3 = "UPDATE avoir
              SET id_oeuvre = :ovr,
              id_pic = :pic,
              id_tri = 1,
              id_type = :type
              WHERE id_pic = {$_SESSION['loadedOvr2D']};";
            } else {
              $sql3 = "INSERT INTO avoir(id_oeuvre, id_pic, id_tri, id_type)
                      VALUES(:ovr, :pic, 1, :type);";
            }
            $qry3 = $db->prepare($sql3);
            $qry3->bindValue(':ovr', $_SESSION['currentOvr'], PDO::PARAM_INT);
            $qry3->bindValue(':pic', $lastID2, PDO::PARAM_INT);
            $qry3->bindValue(':type', $idType, PDO::PARAM_INT);
            try { $qry3->execute(); }
            catch (Exception $e) { $warning = "<p class=\"warning\">Une erreur est survenue !</p>"; }
            break;
          case '3D':
            if(!empty($_SESSION['loadedOvr3D']))
            {
              $sql2 = "UPDATE trois_dimensions
              SET longueur_tri = :longueur,
              largeur_tri = :largeur,
              hauteur_tri = :hauteur
              WHERE id_tri = {$_SESSION['loadedOvr3D']};";
            } else {
              $sql2 = "INSERT INTO trois_dimensions(longueur_tri, largeur_tri, hauteur_tri)
                      VALUES(:longueur, :largeur, :hauteur);";
            }
            $qry2 = $db->prepare($sql2);
            $qry2->bindValue(':longueur', $longueur, PDO::PARAM_STR);
            $qry2->bindValue(':hauteur', $hauteur, PDO::PARAM_STR);
            $qry2->bindValue(':largeur', $largeur, PDO::PARAM_STR);
            try { $qry2->execute(); }
            catch (Exception $e) { $warning = "<p class=\"warning\">Une erreur est survenue !</p>"; }
            $lastID2 = $db->lastInsertId();
            if(!empty($_SESSION['loadedOvr2D']))
            {
              $sql3 = "UPDATE avoir
              SET id_oeuvre = :ovr,
              id_pic = 1,
              id_tri = :tri,
              id_type = :type
              WHERE id_tri = {$_SESSION['loadedOvr3D']};";
            } else {
              $sql3 = "INSERT INTO avoir(id_oeuvre, id_pic, id_tri, id_type)
                      VALUES(:ovr, 1, :tri, :type);";
            }
            $qry3 = $db->prepare($sql3);
            $qry3->bindValue(':ovr', $_SESSION['currentOvr'], PDO::PARAM_INT);
            $qry3->bindValue(':tri', $lastID2, PDO::PARAM_INT);
            $qry3->bindValue(':type', $idType, PDO::PARAM_INT);
            try { $qry3->execute(); }
            catch (Exception $e) { $warning = "<p class=\"warning\">$e</p>"; }
            break;
          default: // 0D
            if(isset($_SESSION['loadedOvr']))
            {
              $sql3 = "UPDATE avoir
              SET id_oeuvre = :ovr,
              id_pic = 1,
              id_tri = 1,
              id_type = :type
              WHERE id_oeuvre = {$_SESSION['loadedOvr']};";
            } else {
              $sql3 = "INSERT INTO avoir(id_oeuvre, id_pic, id_tri, id_type)
                      VALUES(:ovr, 1, 1, :type);";
            }
            $qry3 = $db->prepare($sql3);
            $qry3->bindValue(':ovr', $_SESSION['currentOvr'], PDO::PARAM_INT);
            $qry3->bindValue(':type', $idType, PDO::PARAM_INT);
            try { $qry3->execute(); }
            catch (Exception $e) { $warning = "<p class=\"warning\">$e</p>"; }
            break;
        }
      }
    /**********/
    /* INSERT */
    /**********/
    } else {
      // Insertion des dimensions
      switch ($catType)
      {
        case '2D':
          $sql2 = "INSERT INTO deux_dimensions(longueur_pic, hauteur_pic)
                   VALUES(:longueur, :hauteur);";
          $qry2 = $db->prepare($sql2);
          $qry2->bindValue(':longueur', $longueur, PDO::PARAM_STR);
          $qry2->bindValue(':hauteur', $hauteur, PDO::PARAM_STR);
          try { $qry2->execute(); }
          catch (Exception $e) { $warning = "<p class=\"warning\">$e</p>"; }
          $lastID2 = $db->lastInsertId();
          $sql3 = "INSERT INTO avoir(id_oeuvre, id_pic, id_tri, id_type)
                   VALUES(:ovr, :pic, 1, :type);";
          $qry3 = $db->prepare($sql3);
          $qry3->bindValue(':ovr', $_SESSION['currentOvr'], PDO::PARAM_INT);
          $qry3->bindValue(':pic', $lastID2, PDO::PARAM_INT);
          $qry3->bindValue(':type', $idType, PDO::PARAM_INT);
          try { $qry3->execute(); }
          catch (Exception $e) { $warning = "<p class=\"warning\">Une erreur est survenue !</p>"; }
          break;
        case '3D':
          $sql2 = "INSERT INTO trois_dimensions(longueur_tri, largeur_tri, hauteur_tri)
                   VALUES(:longueur, :largeur, :hauteur);";
          $qry2 = $db->prepare($sql2);
          $qry2->bindValue(':longueur', $longueur, PDO::PARAM_STR);
          $qry2->bindValue(':hauteur', $hauteur, PDO::PARAM_STR);
          $qry2->bindValue(':largeur', $largeur, PDO::PARAM_STR);
          try { $qry2->execute(); }
          catch (Exception $e) { $warning = "<p class=\"warning\">Une erreur est survenue !</p>"; }
          $lastID2 = $db->lastInsertId();
          $sql3 = "INSERT INTO avoir(id_oeuvre, id_pic, id_tri, id_type)
                   VALUES(:ovr, 1, :tri, :type);";
          $qry3 = $db->prepare($sql3);
          $qry3->bindValue(':ovr', $_SESSION['currentOvr'], PDO::PARAM_INT);
          $qry3->bindValue(':tri', $lastID2, PDO::PARAM_INT);
          $qry3->bindValue(':type', $idType, PDO::PARAM_INT);
          try { $qry3->execute(); }
          catch (Exception $e) { $warning = "<p class=\"warning\">$e</p>"; }
          break;
        default: // 0D
          $sql3 = "INSERT INTO avoir(id_oeuvre, id_pic, id_tri, id_type)
                   VALUES(:ovr, 1, 1, :type);";
          $qry3 = $db->prepare($sql3);
          $qry3->bindValue(':ovr', $_SESSION['currentOvr'], PDO::PARAM_INT);
          $qry3->bindValue(':type', $idType, PDO::PARAM_INT);
          try { $qry3->execute(); }
          catch (Exception $e) { $warning = "<p class=\"warning\">$e</p>"; }
          break;
      }
    }

    // Insertion des données en anglais et en chinois avec prise en compte de la MAJ
    $sql4 = "INSERT INTO oeuvres_trad(titre_oeuvre_trad, descriptif_oeuvre_trad, id_langue, id_oeuvre)
    VALUES(:titre_en, :desc_en, 1, :id_oeuvre), (:titre_zh, :desc_zh, 2, :id_oeuvre)
    ON DUPLICATE KEY UPDATE titre_oeuvre_trad = values(titre_oeuvre_trad), descriptif_oeuvre_trad = values(descriptif_oeuvre_trad);";

    $qry4 = $db->prepare($sql4);
    $qry4->bindValue(':titre_en', $titre_en, PDO::PARAM_STR);
    $qry4->bindValue(':titre_zh', $titre_zh, PDO::PARAM_STR);
    $qry4->bindValue(':desc_en', $desc_en, PDO::PARAM_STR);
    $qry4->bindValue(':desc_zh', $desc_zh, PDO::PARAM_STR);
    $qry4->bindValue(':id_oeuvre', $_SESSION['currentOvr'], PDO::PARAM_INT);

    try
    {
      $qry4->execute();
      header('Location: editer-oeuvre.php');
      exit();
    }
    catch (Exception $e)
    {
      // Traductions facultatives
      header('Location: editer-oeuvre.php');
      exit();
    }
  } else {
    unset($_SESSION['loadedOvr']);
    unset($_SESSION['loadedOvrArt']);
    unset($_SESSION['loadedOvr2D']);
    unset($_SESSION['loadedOvr3D']);
    unset($_SESSION['loadedOvrCat']);
    unset($_SESSION['loadedOvrLib']);
    unset($_SESSION['currentOvr']);
    unset($_SESSION['nomCurrentOvr']);
    $warning = "<p class=\"warning\">Les champs obligatoires ne sont pas correctement remplis !</p>";
  }
}

// ÉTAPE 2
// Traitement notifications
if(isset($_GET['status']))
{
  switch ($_GET['status'])
  {
    case 'upload':
      $warning = "<p class=\"warning\">Transmission du fichier réussie !</p>\r\n";
      break;
    case 'delete':
      $warning = "<p class=\"warning\">Supression du fichier réussie !</p>\r\n";
      break;
    default:
      break;
  }
}

// Reset
if(isset($_POST['coll2-reset']))
{
  // Destruction des variables de session
  unset($_SESSION['loadedOvr']);
  unset($_SESSION['loadedOvrArt']);
  unset($_SESSION['loadedOvr2D']);
  unset($_SESSION['loadedOvr3D']);
  unset($_SESSION['loadedOvrCat']);
  unset($_SESSION['loadedOvrLib']);
  unset($_SESSION['currentOvr']);
  unset($_SESSION['nomCurrentOvr']);
  header('Location: editer-oeuvre.php');
  exit();
}

if(isset($_SESSION['currentOvr']))
{
  // Chargement des médias
  $listMedia = ""; // Var init
  $sql5 = "SELECT nom_media, A.id_media, type_media
  FROM medias AS M
  INNER JOIN accompagner AS A ON M.id_media = A.id_media
  WHERE A.id_oeuvre = {$_SESSION['currentOvr']}
  ORDER BY type_media;";
  $qry5 = $db->query($sql5);

  foreach ($qry5 as $media)
  {
    switch ($media['type_media']) {
      case 'son':
      $listMedia .= "\t\t<div class=\"upload-preview\">\r
         <figure>\r
          \t<figcaption>{$media['nom_media']}</figcaption>\r
          \t<audio controls src=\"../media/{$_SESSION['currentOvr']}/{$media['nom_media']}\">\r
            \tYour browser does not support the <code>audio</code> element.\r
          \t</audio>\r
         </figure>\r
         <form method=\"post\" action=\"{$_SERVER['PHP_SELF']}\">\r
           \t<input type=\"hidden\" name=\"del\" value=\"{$media['nom_media']}\">\r
           \t<input type=\"hidden\" name=\"del2\" value=\"{$media['id_media']}\">\r
           \t<button type=\"submit\"><i class=\"fas fa-trash-alt\"></i></button>\r
         </form>\r
      \t</div>\r\n";
        break;

      case 'video':
      $listMedia .= "\t\t<div class=\"upload-preview\">\r
        <video controls width=\"auto\">\r
          \t<source src=\"../media/{$_SESSION['currentOvr']}/{$media['nom_media']}\" type=\"video/mp4\">\r
            \tSorry, your browser doesn't support embedded videos.\r
        </video>\r
        <form method=\"post\" action=\"{$_SERVER['PHP_SELF']}\">\r
          \t<input type=\"hidden\" name=\"del\" value=\"{$media['nom_media']}\">\r
          \t<input type=\"hidden\" name=\"del2\" value=\"{$media['id_media']}\">\r
          \t<button type=\"submit\"><i class=\"fas fa-trash-alt\"></i></button>\r
        </form>\r
     \t</div>\r\n";
        break;

      default: // image
        $listMedia .= "\t\t<div class=\"upload-preview\">\r
           \t<img src=\"../media/{$_SESSION['currentOvr']}/{$media['nom_media']}\" alt=\"media\">\r
           \t<form method=\"post\" action=\"{$_SERVER['PHP_SELF']}\">\r
             \t<input type=\"hidden\" name=\"del\" value=\"{$media['nom_media']}\">\r
             \t<input type=\"hidden\" name=\"del2\" value=\"{$media['id_media']}\">\r
             \t<button type=\"submit\"><i class=\"fas fa-trash-alt\"></i></button>\r
           \t</form>\r
        </div>\r\n";
        break;
    }
  }
}

// Delete média
if(isset($_POST['del']) && !empty($_POST['del']))
{
  // Suppresion dans la DB
  $sql6 = "DELETE FROM accompagner WHERE id_media = {$_POST['del2']}";
  $qry6 = $db->exec($sql6);
  $sql6b = "DELETE FROM medias WHERE id_media = {$_POST['del2']}";
  $qry6b = $db->exec($sql6b);

  // Suppresion du fichier
  $path = substr_replace((__dir__), "", -10);
  unlink($path."/media/".$_SESSION['currentOvr']."/".$_POST['del']);

  // REdirection
  header('Location: editer-oeuvre.php?status=delete');
  exit();
}

// Fichier de traitement de l'upload
require_once('inc/upload-ovr.inc.php');
?>
