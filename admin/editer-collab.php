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

// Reset
if(isset($_POST['editerCollab-reset']))
{
  // Destruction des variables de session
  unset($_SESSION['collab']);
  unset($_SESSION['pwd']);
  header('Location: editer-collab.php');
  exit();
}

// Reset au rechargement de la page
if(isset($_SESSION['collab']) && !isset($_POST['editerCollaborateur']))
{
  unset($_SESSION['collab']);
  unset($_SESSION['pwd']);
}

//etape 1
// récupération info collaborateurs
if (isset($_GET['collab']))
{
  $submitVal = "Modifier";
  $infoCollab = "";
  $collab = $_GET['collab'];
  $requete1="SELECT nom_co, prenom_co, email_co, id_co, pwd_co FROM collaborateurs WHERE id_co='$collab'";
  $reponse=$db->query($requete1);

  foreach ($reponse as $info)
  {
    $nom  = $info['nom_co'];
    $prenom = $info['prenom_co'];
    $email = $info['email_co'];
    $trigramme = $info['id_co'];
    $pwd = $info['pwd_co'];
  }
}

if (isset($_SESSION['collab']))
{
  $submitVal = "Modifier";
}

// Création de la variable de session
if(isset($_GET['collab']) && strlen($_GET['collab']) == 3)
{
    $_SESSION['collab'] = $_GET['collab'];
    $_SESSION['pwd'] = $pwd;
}

// etape 2
// traitement formulaire
if (isset($_POST['editerCollaborateur']))
{
  if (
  (isset($_POST['nom']) && !empty($_POST['nom']) && (strlen($_POST['nom']) <= 100))
  &&
  (isset($_POST['prenom']) && !empty($_POST['prenom']) && (strlen($_POST['prenom']) <= 100))
  &&
  (isset($_POST['email']) && !empty($_POST['email']) && (strlen($_POST['email']) <= 100))
  &&
  (isset($_POST['trigramme']) && !empty($_POST['trigramme']) && (strlen($_POST['trigramme']) == 3)) )
  {
    $nom = mb_strToUpper(trim($_POST['nom']));
    $prenom = ucFirst(mb_strToLower(trim($_POST['prenom'])));
    $email = mb_strToLower(trim($_POST['email']));
    $trigramme = $_POST['trigramme'];
    $pwd = pwdGen();

    if(isset($_SESSION['collab']) && isset($_POST['modifpwd']))
    {
      // On régénere le mot de passe
      $requete2 = "UPDATE collaborateurs
                    SET id_co = :trigramme,
                    nom_co = :nom,
                    prenom_co = :prenom,
                    email_co = :email,
                    pwd_co = :pwd
                    WHERE id_co = '{$_SESSION['collab']}';";
    } else if (isset($_SESSION['collab']) && !isset($_POST['modifpwd'])) {
      // On conserve le mot de passe
      $pwd = $_SESSION['pwd'];
      $requete2 = "UPDATE collaborateurs
                    SET id_co = :trigramme,
                    nom_co = :nom,
                    prenom_co = :prenom,
                    email_co = :email,
                    pwd_co = :pwd
                    WHERE id_co = '{$_SESSION['collab']}';";
    } else {
      // Mode créer
      $requete2 = "INSERT INTO collaborateurs(id_co, nom_co, prenom_co, email_co, pwd_co)
                    VALUES(:trigramme, :nom, :prenom, :email, :pwd);";
    }

    $reponse2 = $db->prepare($requete2);
    $reponse2->bindValue(':trigramme', $trigramme, PDO::PARAM_STR);
    $reponse2->bindValue(':nom', $nom, PDO::PARAM_STR);
    $reponse2->bindValue(':prenom', $prenom, PDO::PARAM_STR);
    $reponse2->bindValue(':email', $email, PDO::PARAM_STR);
    $reponse2->bindValue(':pwd', $pwd, PDO::PARAM_STR);

    try
    {
      $reponse2->execute();
      if (isset($_SESSION['collab']))
      {
        $warning = "<p class='warning'>Modifications enregistrées !</p>";
      } else {
        $warning = "<p class='warning'>Utilisateur enregistré !</p>";
        $warning .= "<p class='warning'>Le mot de passe de l'utilisateur est : {$pwd}</p>";
      }
    }
    catch (Exception $e)
    {
      $warning = "<p class='warning'>Une erreur est survenue !<br>Vérifiez si le trigramme n'est pas déjà attribué !</p>";
      //$warning = "<p class='warning'>{$e}</p>";
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
    <p><label for="email">Email* :</label> <input type="text" id="email" name="email" maxlength="100" value="<?php echo $email ?>"></p>
    <p><label for="trigramme">Trigramme* :</label> <input type="text" id="trigramme" name="trigramme" maxlength="3" value="<?php echo $trigramme ?>"></p>


<?php if(isset($_GET['collab']) || isset($_SESSION['collab'])){ ?>
    <p><label for="pwd">Mot de passe :</label> <input type="text" id="pwd" name="pwd" maxlength="10" disabled value="<?php echo $pwd ?>"></p>
    <p><input type="checkbox" id="modifpwd" name="modifpwd" value="true"> <label for="modifPwd" class="inline-input">Modifier Mot de passe </label></p>
<?php } ?>
    <p>
      <input type="submit" name="editerCollaborateur" id="editCollab" value="<?php echo $submitVal; ?>">
      <input type="submit" name="editerCollab-reset" id="editerCollab-reset" value="Annuler">
    </p>
  </form>
<?php require_once('inc/foot.inc.php'); ?>
