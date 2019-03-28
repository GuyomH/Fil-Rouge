<?php
/*********/
/* UPLOAD*/
/*********/
// String $chemin = chemin + nom du répertoire
function chkDir($chemin)
{
  if (!file_exists($chemin))
  {
    // Création
    mkdir($chemin, 0777);
  }
}

// Sanitizer pour upload de fichiers :
// Array $fichier = tableau sous la forme $_FILES['nomDuFichier']
// La fonction retourne FALSE si le type du fichier est incorrect
function sanitize($fichier)
{
  $finfo = finfo_open(FILEINFO_MIME_TYPE); // Retourne le type mime à l'extension mimetype
  $mime = finfo_file($finfo, $fichier['tmp_name']);
  finfo_close($finfo);
  $getName = $fichier['name'];

  // tableau des types MIME autorisés
  $mimeArray = ["image/jpeg" => ".jpg", "audio/mpeg" => ".mp3", "audio/mpeg3" => ".mp3", "audio/x-mpeg-3" => ".mp3", "video/mp4" => ".mp4"];

  if( array_key_exists($mime, $mimeArray) ) // Si le type mime ($mime) correspond à une clé du tableau $mimeArray
  {
    $type = $mimeArray[$mime]; // ... On valide l'extension de fichier et on formate le nom du fichier
    $fileName = baseName(strToLower(trim($getName)), $type);
    // On remplace les caractères interdits par le "-"
    $fileName = preg_replace('/[^a-z0-9]+/', '-', $fileName);
    $fileName = $fileName . date("-Y-m-d") . $type;
    return $fileName;
  } else {
    return FALSE;
  }
}

// MAX_FILE_SIZE
$baseSize = 1048576; // 1 MO
$multiplier = 2;
$size = intval($baseSize * $multiplier);

if(isset($_POST['upload']))
{
  // Traitement de l'upload simple de fichier
  if($_FILES['fichier']['error'] == 0)
  {
    // Chemin du répertoire + sous repertoire (id de l'oeuvre)
    $uploaddir = substr_replace((__dir__), "", -10) . "/media/" . $_SESSION['currentOvr'] . "/";
    // Chemin + nom du fichier assaini par la foncion "assainir"
    $name = sanitize($_FILES['fichier']);
    $uploadfile = $uploaddir . $name;
    //Function de vérification avec le paramètre TRUE (on vide le répertoire s'il contient des fichiers)
    chkDir($uploaddir);
    // Traitement de l'upload
    if(!$name)
    {
      $warning = "<p class=\"warning\">Type de fichier incorrect !</p>\r\n";
    } else {
      if (move_uploaded_file($_FILES['fichier']['tmp_name'], $uploadfile))
      {
          // En cas de réussite de l'upload on enregistre le fichier dans la base de données
          $extension = substr($name, -4);
          switch ($extension)
          {
            case '.mp3':
              $typeMedia = "son";
              break;
            case '.mp4':
              $typeMedia = "video";
              break;
            default:
              $typeMedia = "image";
              break;
          }

          $sql = "INSERT INTO medias (type_media, nom_media)
          VALUES ('$typeMedia', '$name');";
          $qry = $db->exec($sql);
          $lastID = $db->lastInsertId();
          $sql2 = "INSERT INTO accompagner (id_media, id_oeuvre)
          VALUES ({$lastID}, {$_SESSION['currentOvr']});";
          $qry2 = $db->exec($sql2);

          header('Location: editer-oeuvre.php?status=upload');
          exit();
      } else {
          $warning = "<p class=\"warning\">Attaque potentielle par téléchargement de fichiers !</p>\r\n";
      }
    }
  } else {
    $warning = "<p class=\"warning\">Aucun fichier chargé !</p>\r\n";
  }
  // Fin Traitement de l'upload fichier
}
?>
