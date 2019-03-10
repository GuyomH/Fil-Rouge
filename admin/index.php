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

/****************************/
/* CONNEXION COLLABORATEURS */
/****************************/
// var init
$warning = "";
$fail = "<p class=\"warning\">Identifiants incorrects !</p>";

if(isset($_POST['connexion']))
{
  if((isset($_POST['login']) && strlen($_POST['login']) == 3) &&
  (isset($_POST['password']) && strlen($_POST['password']) == 10))
  {
    $sql = "SELECT id_co, privilege_co, nom_co, prenom_co
    FROM collaborateurs
    WHERE id_co = '{$_POST['login']}' AND pwd_co = '{$_POST['password']}'
    LIMIT 1;";

    try
    {
      $qry = $db->query($sql);
      $res = $qry->fetch();
    }
    catch (Exception $e)
    {
      $res = FALSE;
      if(stristr($e->getMessage(), "access violation"))
      {
        $warning = "<p class=\"warning\">Tentative de violation !</p>";
      } else {
        $warning = "<p class=\"warning\">Erreur : " . $e->getMessage() . "</p>";
      }
    }

    if($res != FALSE)
        {
          $accessData = json_encode(['trigramme' => $res['id_co'], 'role' => $res['privilege_co'], 'nom' => $res['nom_co'], 'prenom' => $res['prenom_co']]);
          $_SESSION['identification'] = $accessData;
          header('Location: index.php');
          exit();
        } else {
          if(empty($warning)) { $warning = $fail; }
        }
  } else {
    $warning = $fail;
  }
}
?>
<?php require_once('inc/head.inc.php'); ?>
<?php if(isset($_SESSION['identification'])){ ?>
          <p>Bonjour <strong><?php echo $prenom . " " . $nom; ?></strong>, vous êtes connecté(e) en tant qu'<strong><?php echo $role; ?></strong>.</p>
          <p>Vous pouvez vous déconnecter à tout moment en cliquant sur le bouton ci-dessous :</p>
          <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <p><input type="submit" name="destroy" value="DÉCONNEXION"></p>
          </form>
<?php } else { ?>
          <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <p><label for="login">Identifiant :</label> <input type="text" id="login" name="login" maxlength="3" pattern="[A-Za-z]{3}" title="3 caractères alphabétiques"></p>
            <p><label for="password">Mot de passe :</label> <input type="password" id="password" name="password" maxlength="10" pattern=".{10}" title="séquence de 10 caractères"><i class="far fa-eye"></i></p><!-- <i class="far fa-eye"></i> -->
            <p><input type="submit" name="connexion" value="CONNEXION"></p>
          </form>
          <?php echo $warning; ?>

          <!-- TEST -->
          <pre>
            dhe RO(2IQOpF9
            fjl :9Bqr<,|pl
            dhe ' or 1 --
          </pre>
          <!-- TEST / FIN -->
<?php } ?>
<?php require_once('inc/foot.inc.php'); ?>
