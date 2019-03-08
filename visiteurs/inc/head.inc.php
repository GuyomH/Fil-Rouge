<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <title><?php echo $title; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="description" content="<?php echo $desc; ?>">
    <meta name="author" content="Guillaume Hénaud, Florent Dixneuf">
    <link rel="shortcut icon" href="img/favicon.ico">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
  </head>
  <body>

    <header>
        <div class="container">
          <p id="logo"><a href="index.php" title="Page d'accueil"><img src="img/logo_grand_angle.png" alt="logo Grand Angle"></a></p>
          <ul id="langues" class="container">
            <li><a href="<?php echo $pageName; ?>.php?lang=fr<?php echo $param; ?>" title="français"><img src="img/fr.svg" alt="français"></a></li>
            <li><a href="<?php echo $pageName; ?>.php?lang=en<?php echo $param; ?>" title="anglais / english"><img src="img/gb.svg" alt="anglais"></a></li>
            <li><a href="<?php echo $pageName; ?>.php?lang=zh<?php echo $param; ?>" title="chinois / 中文"><img src="img/cn.svg" alt="chinois"></a></li>
          </ul>
        </div>
        <hr>
        <div class="container">
        <nav>
          <ul class="container">
            <li><?php echo $indexLnk; ?></li>
            <li><?php echo $visitLnk; ?></li>
          </ul>
        </nav>
        </div>
    </header>
