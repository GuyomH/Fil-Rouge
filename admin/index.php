<?php
// SESSION INIT
session_start();

/****************/
/* CONNEXION DB */
/****************/
require_once('inc/pdo.inc.php');

/********************/
/* FONCTIONS UTILES */
/********************/
/* TODO */

/****************************/
/* GESTION DE LA NAVIGATION */
/****************************/
/* TODO (apparence du menu, check variables de sessions) */

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
    $sql = "SELECT privilege_co, nom_co, prenom_co
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
          $privi = $res['privilege_co'];
          $nom = $res['nom_co'];
          $prenom = $res['prenom_co'];
          // $acces = json_encode(['id' => $id, 'nom' => $nom, 'role' => $role]);  // Json method
          // setCookie("acces", $acces, time()+3600);
          // header('Location: admin.php');
        } else {
          if(empty($warning)) { $warning = $fail; }
        }
  } else {
    $warning = $fail;
  }
}
?>
<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <title><?php /*echo $title;*/ ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="description" content="<?php /*echo $desc;*/ ?>">
    <meta name="author" content="Guillaume Hénaud, Florent Dixneuf">
    <link rel="shortcut icon" href="img/favicon.ico">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
  </head>
  <body>

    <div id="layout">
      <nav>
        <img src="img/logo_grand_angle.png" alt="logo Grand Angle" id="logo">
        <ul>
          <li><a href="index.php" title="Connexion / Déconnexion">CONNEXION</a></li>
          <li>
            <div class="menu-titre">EXPOSITIONS</div>
            <ul>
              <li><a href="liste-expo.php" title="Liste des expositions">Liste</a></li>
              <li><a href="editer-expo.php" title="Créer ou éditer une exposition">Créer</a></li>
            </ul>
          </li>
          <li>
            <div class="menu-titre">GESTION</div>
            <ul>
              <li><a href="editer-oeuvre.php" title="Créer ou éditer une oeuvre">Oeuvres</a></li>
              <li><a href="editer-artiste.php" title="Créer ou éditer un artiste">Artistes</a></li>
              <li><a href="editer-collectif.php" title="Créer ou éditer un collectif">Collectifs</a></li>
            </ul>
          </li>
          <li>
            <div class="menu-titre">COLLABORATEURS</div>
            <ul>
              <li><a href="liste-collab.php" title="Liste des collaborateurs">Liste</a></li>
              <li><a href="editer-collab.php" title="Créer ou éditer un collaborateur">Ajouter</a></li>
            </ul>
          </li>
          <li><a href="traduction.php" title="Trouver les contenus non traduits">TRADUCTIONS</a></li>
          <li><a href="statistique.php" title="Statistiques de consultation de la partie publique du site">STATISTIQUES</a></li>
        </ul>
      </nav>
      <div id="header-main">
        <div id="header-bkg">
          <header>
            <h1>► CONNEXION</h1>
          </header>
        </div>
        <main>
          <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" id="connexion">
            <p><label for="login">Identifiant</label> <input type="text" id="login" name="login" maxlength="3" pattern="[A-Za-z]{3}" title="3 caractères alphabétiques"></p>
            <p><label for="password">Mot de passe</label> <input type="password" id="password" name="password" maxlength="10" pattern=".{10}" title="séquence de 10 caractères"><i class="far fa-eye"></i></p>
            <!-- <i class="far fa-eye"></i> -->
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
        </main>
      </div>
    </div>

    <footer></footer>

  </body>
</html>
