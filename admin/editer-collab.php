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

/******************************/
/* EDITION DES COLLABORATEURS */
/******************************/

// var init
$warning = "";
$nom = "";
$prenom = "";
$email = "";
$trigramme = "";
$submitVal = "Créer";

//etape 1

// récupération info collaborateurs
if (isset($_GET['collab']))
{
  $submitVal = "Modifier";
  $infoCollab = "";
  $collab = $_GET['collab'];
  $requete1="SELECT nom_co, prenom_co, email_co, id_co FROM collaborateurs WHERE id_co='$collab'";
  $reponse=$db->query($requete1);

  foreach ($reponse as $info)
  {
    $nom  = $info['nom_co'];
    $prenom = $info['prenom_co'];
    $email = $info['email_co'];
    $trigramme = $info['id_co'];
  }
}

// etape 2
// traitement formulaire
if (isset($_POST['editerCollaborateur']))
{
  if
  (
  (isset($_POST['nom']) && !empty($_POST['nom']) && (strlen($_POST['nom']) <= 100))
  &&
  (isset($_POST['prenom']) && !empty($_POST['prenom']) && (strlen($_POST['prenom']) <= 100))
  &&
  (isset($_POST['email']) && !empty($_POST['email']) && (strlen($_POST['email']) <= 100))
  &&
  (isset($_POST['trigramme']) && !empty($_POST['trigramme']) && (strlen($_POST['trigramme']) == 3))
  )
  {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $trigramme = $_POST['trigramme'];
    $pwd = pwdGen();

    $requete2 = "INSERT INTO collaborateurs(id_co, nom_co, prenom_co, email_co, pwd_co)
    VALUES(:trigramme, :nom, :prenom, :email, :pwd);";

    $reponse2 = $db->prepare($requete2);
    $reponse2->bindValue(':trigramme', $trigramme, PDO::PARAM_STR);
    $reponse2->bindValue(':nom', $nom, PDO::PARAM_STR);
    $reponse2->bindValue(':prenom', $prenom, PDO::PARAM_STR);
    $reponse2->bindValue(':email', $email, PDO::PARAM_STR);
    $reponse2->bindValue(':pwd', $pwd, PDO::PARAM_STR);

    try
    {
      $reponse2->execute();
      $warning = "<p class='warning'>Utilisateur enregistré !</p>";
    }
    catch (Exception $e)
    {
      $warning = "<p class='warning'>Une erreur est survenue !</p>";
    }
  } else {
    $warning = "<p class='warning'>Le formulaire n'a pas été correctement rempli !</p>";
  }
}
?>
<?php require_once('inc/head.inc.php'); ?>
          <!-- FRONT DE LA PAGE -->
  <?php echo $warning; ?>
  <form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" class="wide">
    <p><label for="nom">Nom* :</label> <input type="text" id="nom" name="nom" maxlength="100" value="<?php echo $nom ?>"></p>
    <p><label for="prenom">Prénom* :</label> <input type="text" id="prenom" name="prenom" maxlength="100" value="<?php echo $prenom ?>"></p>
    <p><label for="email">Email :</label> <input type="text" id="email" name="email" maxlength="100" value="<?php echo $email ?>"></p>
    <p><label for="trigramme">Trigramme :</label> <input type="text" id="trigramme" name="trigramme" maxlength="3" value="<?php echo $trigramme ?>"></p>
    <p>
      <input type="submit" name="editerCollaborateur" id="editCollab" value="<?php echo $submitVal; ?>">
      <input type="submit" name="editerCollaborateur-reset" id="editCollab-reset" value="Annuler">
    </p>
  </form>
<?php require_once('inc/foot.inc.php'); ?>
