<?php
// var init
$warning = "";
$titreVal = "";
$titreValEn = "";
$titreValZh = "";
$descVal = "";
$descValEn = "";
$descValZh = "";
$debutVal = "";
$finVal = "";

// ÉTAPE 1
// Reset
if(isset($_POST['expo1-reset']))
{
  header('Location: editer-expo.php');
  exit();
}
// Traitement
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

  // A. Vérification du remplissage du formulaire
  if( isset($_POST['titre']) && !empty($_POST['titre']) && (strlen($_POST['titre']) <= 500) &&
  isset($_POST['desc']) && !empty($_POST['desc']) && (strlen($_POST['desc']) <= 1000) &&
  isset($_POST['debut']) && dateChecker($_POST['debut']) &&
  isset($_POST['fin']) && dateChecker($_POST['fin']) )
  {
    // B. Vérification que la date de fin est supérieure à celle de début
    $debut = $_POST['debut'];
    $fin = $_POST['fin'];
    $start = date_timestamp_get(date_create($debut));
    $end = date_timestamp_get(date_create($fin));
    if($start < $end)
    {
      // C. Vérification du non chevauchement des dates
      $sql0 = "SELECT COUNT(*) AS nb_expo
      FROM expositions
      WHERE ('$debut' BETWEEN debut_expo AND fin_expo) OR ('$fin' BETWEEN debut_expo AND fin_expo);";
      $qry0 = $db->query($sql0);
      $res0 = $qry0->fetch();
      $overlap = intval($res0['nb_expo']);

      if($overlap == 0)
      {
        // D. Traitement du reste du formulaire
        // Variabilisation
        $titre = trim($_POST['titre']);
        $desc = trim($_POST['desc']);

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
          header('Location: editer-expo.php?return=false');
          exit();
        }

        // Récupération de l'ID et du titre
        $_SESSION['currentExpo'] = $db->lastInsertId();
        $_SESSION['titreCurrentExpo'] = $titre;

        // Insertion des données en anglais et en chinois
        $sql2 = "INSERT INTO expositions_trad(titre_expo_trad, descriptif_expo_trad, id_langue, id_expo)
        VALUES(:titre_en, :desc_en, 1, :id_expo), (:titre_zh, :desc_zh, 2, :id_expo);";
        $qry2 = $db->prepare($sql2);
        $qry2->bindValue(':titre_en', $titre_en, PDO::PARAM_STR);
        $qry2->bindValue(':titre_zh', $titre_zh, PDO::PARAM_STR);
        $qry2->bindValue(':desc_en', $desc_en, PDO::PARAM_STR);
        $qry2->bindValue(':desc_zh', $desc_zh, PDO::PARAM_STR);
        $qry2->bindValue(':id_expo', $_SESSION['currentExpo'], PDO::PARAM_INT);
        try
        {
          $qry2->execute();
          header('Location: editer-expo.php');
          exit();
        }
        catch (Exception $e)
        {
          // Traductions facultatives
          header('Location: editer-expo.php');
          exit();
        }
      } else {
        $warning = "<p class=\"warning\">La date de début et/ou de fin correspondent à une autre exposition !</p>";
      }
    } else {
      $warning = "<p class=\"warning\">La date de fin est supérieure à la date de début !</p>";
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
  $empTab .= "\t\t\t\t<tr><td><label for=\"emp{$val['num_emp']}\">{$val['num_emp']}</label></td><td><select name=\"emp{$val['num_emp']}\" id=\"emp{$val['num_emp']}\">\r\n\t\t\t\t\t<option hidden value=\"\">Choisir</option>\r\n\t\t\t\t\t<option value=\"\">-</option>\r\n";
    // Récupération des dimensions de l'emplacement
    $sql2 = "SELECT longueur_emp, largeur_emp, hauteur_emp
    FROM emplacements
    WHERE num_emp = '{$val['num_emp']}';";
    $qry2 = $db->query($sql2);
    $dimension = $qry2->fetch(); // 1 résultat attendu
    // Liste des oeuvres qui logent dans l'emplacement
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
      $empTab .= "\t\t\t\t\t<option value=\"{$ovr['id_oeuvre']}\">{$ovr['titre_oeuvre']}</option>\r\n";
    }
    // Liste des oeuvres qui ne logent pas dabs l'emplacement
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

  $empTab .= "\t\t\t\t</select></td><td><input type=\"date\" name=\"date{$val['num_emp']}\" id=\"date{$val['num_emp']}\"></td><td></tr>\r\n";
}

// ÉTAPE 3 / TRAITEMENT
// Reset
if(isset($_POST['expo2-reset']))
{
  unset($_SESSION['currentExpo']);
  unset($_SESSION['titreCurrentExpo']);
  header('Location: editer-expo.php');
  exit();
}
// Traitement
if(isset($_POST['expo2']))
{
  // Récupération des numéros d'emplacement
  $emplacement = [];
  $sql5 = "SELECT num_emp FROM emplacements";
  $qry5 = $db->query($sql5);
  while($emp = $qry5->fetch())
  {
    $emplacement[] = $emp['num_emp'];
  }

  // Création de la requête / part 1
  $sql6 = "INSERT INTO composer (livraison_oeuvre, id_oeuvre, id_expo, num_emp)\r\nVALUES ";

  // Vérif des variables
  foreach ($emplacement as $numEmp)
  {
    if(!empty($_POST['emp'.$numEmp]))
    {
      // Variables dynamiques
      $var = 'emp'.$numEmp;
      $var2 = 'date'.$numEmp;

      if(!empty($_POST['emp'.$numEmp]))
      {
        ${$var} = intval($_POST['emp'.$numEmp]);
      } else {
        ${$var} = "NULL";
      }

      if(!empty($_POST['date'.$numEmp]) && !empty($_POST['emp'.$numEmp]))
      {
        ${$var2} = $db->quote($_POST['date'.$numEmp]);
      } else {
        ${$var2} = "NULL";
      }

      $id = intval($_SESSION['currentExpo']);

      $sql6 .= "(".${$var2}.", ".${$var}.", ".$id.", '".$numEmp."'),\r\n";
    }
  }

  // Création de la requête / part fin
  $sql6 = substr_replace($sql6, ";", -3);

  try
  {
    $qry6 = $db->exec($sql6);
    unset($_SESSION['currentExpo']);
    unset($_SESSION['titreCurrentExpo']);
    header('Location: editer-expo.php?return=true');
    exit();
  }
  catch (Exception $e)
  {
    unset($_SESSION['currentExpo']);
    unset($_SESSION['titreCurrentExpo']);
    header('Location: editer-expo.php?return=false');
    exit();
  }
}
?>
