<?php
if(isset($_GET['lang']) && !empty($_GET['lang']))
{
  switch ($_GET['lang']) {
    case 'en':
      setCookie("lang", "en", time()+3600);
      break;
    case 'zh':
      setCookie("lang", "zh", time()+3600);
      break;
    default:
      setCookie("lang", "fr", time()+3600);
      break;
  }
  header('Location: ' . $_SERVER['PHP_SELF']);
}

$interface_fr = [
  "accueil"=>"Accueil",
  "visite"=>"Visite intéractive",
  "expo_en_cours"=>"Exposition en cours",
  "expo_a_venir"=>"Expositions à venir",
  "no_expo"=>"Pas d'exposition pour le moment !",
  "no_expo_a_venir"=>"Pas d'exposition à venir !",
  "decouvrir"=>"Découvrir",
  "du"=>"Du",
  "au"=>"au",
  "au_bis"=>"",
  "plan_int"=>"Plan intéractif",
  "liste_oeuvre"=>"Liste des oeuvres",
  "titre_oeuvre"=>"Titre",
  "emplacement"=>"Emplacement",
  "artiste"=>"Artiste",
  "collectif"=>"Collectif",
  "fiche_detail"=>"Fiche détaillée",
  "retour_plan"=>"Retour au plan",
  "haut_page"=>"HAUT DE PAGE"
];

$interface_en = [
  "accueil"=>"Home",
  "visite"=>"Interactive visit",
  "expo_en_cours"=>"Exhibition in progress",
  "expo_a_venir"=>"Exhibitions to come",
  "no_expo"=>"No exhibition for the moment !",
  "no_expo_a_venir"=>"No exhibition coming !",
  "decouvrir"=>"Discover",
  "du"=>"From",
  "au"=>"to",
  "au_bis"=>"",
  "plan_int"=>"Interactive map",
  "liste_oeuvre"=>"List of artworks",
  "titre_oeuvre"=>"Title",
  "emplacement"=>"Location",
  "artiste"=>"Artist",
  "collectif"=>"Collective",
  "fiche_detail"=>"Detailed sheet",
  "retour_plan"=>"Back to map",
  "haut_page"=>"TOP"
];

$interface_zh = [
  "accueil"=>"主页",
  "visite"=>"互动访问",
  "expo_en_cours"=>"正在进行的展览",
  "expo_a_venir"=>"即将到来的展览",
  "no_expo"=>"暂时不曝光 ！",
  "no_expo_a_venir"=>"没有展览 ！",
  "decouvrir"=>"发现",
  "du"=>"从",
  "au"=>"年到",
  "au_bis"=>" 年",
  "plan_int"=>"互动地图",
  "liste_oeuvre"=>"作品清单",
  "titre_oeuvre"=>"标题",
  "emplacement"=>"位置",
  "artiste"=>"艺术家",
  "collectif"=>"集体",
  "fiche_detail"=>"详细表",
  "retour_plan"=>"回到计划",
  "haut_page"=>"页面顶部"
];

$sql_fr = [
  "expo_en_cours"=>"SELECT id_expo, titre_expo, debut_expo, fin_expo
  FROM expositions
  WHERE NOW() BETWEEN debut_expo AND fin_expo;",

  "expo_a_venir"=>"SELECT titre_expo, debut_expo, fin_expo
  FROM expositions
  WHERE debut_expo > NOW()
  LIMIT 4;",

  "info_expo"=>"SELECT titre_expo, descriptif_expo, debut_expo, fin_expo
  FROM expositions
  WHERE NOW() BETWEEN debut_expo AND fin_expo;",

  "liste_oeuvre"=>"SELECT EMP.num_emp, O.id_oeuvre,titre_oeuvre, nom_art, prenom_art, nom_col
  FROM expositions AS E
  INNER JOIN Composer AS C ON E.id_expo = C.id_expo
  INNER JOIN emplacements AS EMP ON C.num_emp = EMP.num_emp
  INNER JOIN oeuvres AS O ON C.id_oeuvre = O.id_oeuvre
  INNER JOIN artistes AS A ON O.id_art = A.id_art
  INNER JOIN collectifs AS COL ON A.id_col = COL.id_col
  WHERE NOW() BETWEEN debut_expo AND fin_expo;"
];

$sql_en = [
  "expo_en_cours"=>"SELECT E.id_expo, titre_expo_trad, debut_expo, fin_expo
  FROM expositions AS E
  INNER JOIN expositions_trad AS ET ON E.id_expo = ET.id_expo
  INNER JOIN langues AS L ON ET.id_langue = L.id_langue
  WHERE (NOW() BETWEEN debut_expo AND fin_expo) AND (code_langue = 'en');",

  "expo_a_venir"=>"SELECT E.id_expo, titre_expo_trad, debut_expo, fin_expo
  FROM expositions AS E
  INNER JOIN expositions_trad AS ET ON E.id_expo = ET.id_expo
  INNER JOIN langues AS L ON ET.id_langue = L.id_langue
  WHERE (debut_expo > NOW()) AND (code_langue = 'en')
  LIMIT 4;",

  "info_expo"=>"SELECT titre_expo_trad, descriptif_expo_trad, debut_expo, fin_expo
  FROM expositions AS E
  INNER JOIN expositions_trad AS ET ON E.id_expo = ET.id_expo
  INNER JOIN langues AS L ON ET.id_langue = L.id_langue
  WHERE (NOW() BETWEEN debut_expo AND fin_expo) AND (code_langue = 'en');",

  "liste_oeuvre"=>"SELECT EMP.num_emp, O.id_oeuvre,titre_oeuvre_trad, nom_art, prenom_art, nom_col
  FROM expositions AS E
  INNER JOIN Composer AS C ON E.id_expo = C.id_expo
  INNER JOIN emplacements AS EMP ON C.num_emp = EMP.num_emp
  INNER JOIN oeuvres AS O ON C.id_oeuvre = O.id_oeuvre
  INNER JOIN artistes AS A ON O.id_art = A.id_art
  INNER JOIN collectifs AS COL ON A.id_col = COL.id_col
  INNER JOIN Oeuvres_trad AS OT ON O.id_oeuvre = OT.id_oeuvre
  INNER JOIN Langues AS L ON OT.id_langue = L.id_langue
  WHERE NOW() BETWEEN debut_expo AND fin_expo
  AND (code_langue = 'en');"
];

$sql_zh = [
  "expo_en_cours"=>"SELECT E.id_expo, titre_expo_trad, debut_expo, fin_expo
  FROM expositions AS E
  INNER JOIN expositions_trad AS ET ON E.id_expo = ET.id_expo
  INNER JOIN langues AS L ON ET.id_langue = L.id_langue
  WHERE (NOW() BETWEEN debut_expo AND fin_expo) AND (code_langue = 'zh');",

  "expo_a_venir"=>"SELECT E.id_expo, titre_expo_trad, debut_expo, fin_expo
  FROM expositions AS E
  INNER JOIN expositions_trad AS ET ON E.id_expo = ET.id_expo
  INNER JOIN langues AS L ON ET.id_langue = L.id_langue
  WHERE (debut_expo > NOW()) AND (code_langue = 'zh')
  LIMIT 4;",

  "info_expo"=>"SELECT titre_expo_trad, descriptif_expo_trad, debut_expo, fin_expo
  FROM expositions AS E
  INNER JOIN expositions_trad AS ET ON E.id_expo = ET.id_expo
  INNER JOIN langues AS L ON ET.id_langue = L.id_langue
  WHERE (NOW() BETWEEN debut_expo AND fin_expo) AND (code_langue = 'zh');",

  "liste_oeuvre"=>"SELECT EMP.num_emp, O.id_oeuvre,titre_oeuvre_trad, nom_art, prenom_art, nom_col
  FROM expositions AS E
  INNER JOIN Composer AS C ON E.id_expo = C.id_expo
  INNER JOIN emplacements AS EMP ON C.num_emp = EMP.num_emp
  INNER JOIN oeuvres AS O ON C.id_oeuvre = O.id_oeuvre
  INNER JOIN artistes AS A ON O.id_art = A.id_art
  INNER JOIN collectifs AS COL ON A.id_col = COL.id_col
  INNER JOIN Oeuvres_trad AS OT ON O.id_oeuvre = OT.id_oeuvre
  INNER JOIN Langues AS L ON OT.id_langue = L.id_langue
  WHERE NOW() BETWEEN debut_expo AND fin_expo
  AND (code_langue = 'zh');"
];

if(isset($_COOKIE['lang']))
{
 if($_COOKIE['lang'] == 'en')
 {
   $itf = $interface_en;
   $sql = $sql_en;
 } elseif ($_COOKIE['lang'] == 'zh') {
   $itf = $interface_zh;
   $sql = $sql_zh;
 } else {
   $itf = $interface_fr;
   $sql = $sql_fr;
 }
} else {
 $itf = $interface_fr;
 $sql = $sql_fr;
}
?>
