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
  $newName = $_SESSION['currentArt']; // nom = id du collectif

  // tableau des types MIME autorisés
  $mimeArray = ["image/jpeg" => ".jpg"];

  if( array_key_exists($mime, $mimeArray) ) // Si le type mime ($mime) correspond à une clé du tableau $mimeArray
  {
    $type = $mimeArray[$mime]; // ... On valide l'extension de fichier et on formate le nom du fichier
    $fileName = $newName.$type;
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
    // Chemin du répertoire
    $uploaddir = substr_replace((__dir__), "", -10) . "/artistes/";
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
          $warning = "<p class=\"warning\">Transmission du fichier réussie !</p>\r\n";
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
