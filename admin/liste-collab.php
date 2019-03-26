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
/* LISTE DES COLLABORATEURS */
/****************************/
// Var init
$warning = "";

// Liste des collaborateurs
if (isset($_GET['del']) && strlen($_GET['del'])==3)
{
  $del=$_GET['del'];
  $requete1="DELETE  FROM collaborateurs WHERE id_co = '$del' AND privilege_co = 'user'";
  $db->exec($requete1);
  header('Location:liste-collab.php?warning=true');
  exit();
}

if(isset($_GET['warning']))
{
  $warning = "<p class=\"warning\">L'utilisateur a bien été supprimé !</p>";
}


  $listCollab = "";
  $requete2="SELECT nom_co, prenom_co, email_co, id_co, privilege_co FROM collaborateurs ORDER BY privilege_co ASC";
  $reponse=$db->query($requete2);

  foreach ($reponse as $info)
  {
    $nom  = $info['nom_co'];
    $prenom = $info['prenom_co'];
    $email = $info['email_co'];
    $trigramme = $info['id_co'];
    $role = $info['privilege_co'];
    if ($role == "user")
    {
      $delButton = "<a href=\"liste-collab.php?del={$trigramme}\" title=\"Supprimer le collaborateur\" class=\"delete\"><button>Supprimer</button></a>";
    } else {
      $delButton = "";
    }
    $listCollab .= "\t\t\t<tr><td>$nom</td><td>$prenom</td><td>$email</td><td>$trigramme</td><td>$role</td><td class='list'><a href=\"editer-collab.php?collab={$trigramme}\" title=\"Edition le collaborateur\"><button>Éditer</button></a>  {$delButton}</td></tr>\r\n";

  }
?>
<?php require_once('inc/head.inc.php'); ?>

          <?php echo $warning; ?>

            <table class="list-collab">
            <tr><th>Nom</th><th>Prénom</th><th>Email</th><th>Trigramme</th><th>Role</th></tr>
<?php echo $listCollab; ?>
          </table>
<?php require_once('inc/foot.inc.php'); ?>
