-- Sélectionner le type, l'artiste et/ou le collectif signataire de chaque oeuvre
SELECT titre_oeuvre, T.libelle_type, A.nom_art, C.nom_col
FROM oeuvres AS O
INNER JOIN artistes AS A ON O.id_art = A.id_art
INNER JOIN collectifs AS C ON A.id_col = C.id_col
INNER JOIN avoir AS AV ON O.id_oeuvre = AV.id_oeuvre
INNER JOIN types AS T ON AV.id_type = T.id_type
ORDER BY titre_oeuvre;

-- Sélectionner les dimensions d'une oeuvre par expo
SELECT titre_expo, titre_oeuvre, libelle_type, cat_type, longueur_tri, largeur_tri, hauteur_tri, longueur_pic, hauteur_pic
FROM oeuvres AS O
INNER JOIN avoir AS AV ON O.id_oeuvre = AV.id_oeuvre
INNER JOIN types AS T ON AV.id_type = T.id_type
INNER JOIN deux_dimensions AS DD ON AV.id_pic = DD.id_pic
INNER JOIN trois_dimensions AS DDD ON AV.id_tri = DDD.id_tri
INNER JOIN composer AS C ON O.id_oeuvre = C.id_oeuvre
INNER JOIN expositions AS E ON C.id_expo = E.id_expo
ORDER BY titre_expo, titre_oeuvre;

-- Sélectionner les infos d'une expo et la traduction en anglais (en) et en chinois (zh)
SELECT titre_expo, descriptif_expo, titre_expo_trad, descriptif_expo_trad
FROM expositions AS E
INNER JOIN expositions_trad AS ET ON E.id_expo = ET.id_expo
INNER JOIN langues AS L ON ET.id_langue = L.id_langue
WHERE code_langue = 'en' OR code_langue = 'zh';

-- Sélectionner les infos d'une oeuvres en anglais (en) ou en chinois (zh)
-- EN
SELECT O.id_oeuvre, titre_oeuvre_trad, descriptif_oeuvre_trad, nom_art, prenom_art, bio_art_trad, bio_art_trad, nom_col, info_col_trad
FROM oeuvres AS O
INNER JOIN oeuvres_trad AS OT ON O.id_oeuvre = OT.id_oeuvre
INNER JOIN langues AS L ON OT.id_langue = L.id_langue
INNER JOIN artistes AS A ON O.id_art = A.id_art
INNER JOIN artistes_trad AS AT ON A.id_art = AT.id_art
INNER JOIN langues AS L2 ON AT.id_langue = L2.id_langue
INNER JOIN collectifs AS C ON A.id_col = c.id_col
INNER JOIN collectifs_trad AS CT ON C.id_col = CT.id_col
INNER JOIN langues AS L3 ON CT.id_langue = L3.id_langue
INNER JOIN composer AS COMP ON O.id_oeuvre = COMP.id_oeuvre
INNER JOIN expositions AS E ON COMP.id_expo = E.id_expo
WHERE L.code_langue = 'en'
AND L2.code_langue = 'en'
AND L3.code_langue = 'en'
AND O.id_oeuvre = 2;

-- ZH
SELECT O.id_oeuvre, titre_oeuvre_trad, descriptif_oeuvre_trad, nom_art, prenom_art, bio_art_trad, bio_art_trad, nom_col, info_col_trad
FROM oeuvres AS O
INNER JOIN oeuvres_trad AS OT ON O.id_oeuvre = OT.id_oeuvre
INNER JOIN langues AS L ON OT.id_langue = L.id_langue
INNER JOIN artistes AS A ON O.id_art = A.id_art
INNER JOIN artistes_trad AS AT ON A.id_art = AT.id_art
INNER JOIN langues AS L2 ON AT.id_langue = L2.id_langue
INNER JOIN collectifs AS C ON A.id_col = c.id_col
INNER JOIN collectifs_trad AS CT ON C.id_col = CT.id_col
INNER JOIN langues AS L3 ON CT.id_langue = L3.id_langue
INNER JOIN composer AS COMP ON O.id_oeuvre = COMP.id_oeuvre
INNER JOIN expositions AS E ON COMP.id_expo = E.id_expo
WHERE L.code_langue = 'zh'
AND L2.code_langue = 'zh'
AND L3.code_langue = 'zh'
AND O.id_oeuvre = 2;

-- Sélectionner les médias qui accompagnent chaque oeuvre
SELECT titre_oeuvre, nom_media, type_media
FROM oeuvres AS O
INNER JOIN accompagner AS A ON O.id_oeuvre = A.id_oeuvre
INNER JOIN medias AS M ON A.id_media = M.id_media
WHERE O.id_oeuvre = 1;

-- Vérifier si une expo est en cours
SELECT titre_expo, debut_expo, fin_expo
FROM expositions
WHERE CURDATE() BETWEEN debut_expo AND fin_expo;

-- Sélectionner les 3 prochaines expos à venir
SELECT titre_expo, debut_expo, fin_expo
FROM expositions
WHERE debut_expo > CURDATE()
LIMIT 3;

-- Requête pour contrôler si une date de début ou de fin d'expo se chevauchent avec une autre
SELECT titre_expo, debut_expo, fin_expo
FROM expositions
WHERE ('2019-01-01' BETWEEN debut_expo AND fin_expo) OR ('2019-02-23' BETWEEN debut_expo AND fin_expo);

-- Sélectionner les oeuvres qui n'ont pas été livrées à J - 7 du début d'une expo
SELECT O.id_oeuvre, titre_oeuvre, livraison_oeuvre, titre_expo, debut_expo, DATEDIFF(debut_expo, CURDATE()) AS jours_restants
FROM oeuvres AS O
INNER JOIN composer AS C ON O.id_oeuvre = C.id_oeuvre
INNER JOIN expositions AS E ON C.id_expo = E.id_expo
WHERE (CURDATE() BETWEEN (debut_expo - INTERVAL 7 DAY) AND debut_expo)
AND (ISNULL(livraison_oeuvre)); -- ou (livraison_oeuvre IS NULL)

-- Sélectionner les oeuvres qui n'ont pas été livrées à J - 7 du début d'une expo / VERSION TEST
SELECT O.id_oeuvre, titre_oeuvre, livraison_oeuvre, titre_expo, debut_expo, DATEDIFF(debut_expo, '2019-02-26') AS jours_restants
FROM oeuvres AS O
INNER JOIN composer AS C ON O.id_oeuvre = C.id_oeuvre
INNER JOIN expositions AS E ON C.id_expo = E.id_expo
WHERE ('2019-02-26' BETWEEN (debut_expo - INTERVAL 7 DAY) AND debut_expo)
AND (ISNULL(livraison_oeuvre)); -- ou (livraison_oeuvre IS NULL)

-- Sélectionner les oeuvres liées à une expo avec une date de départ mais sans date d'arrivée
SELECT O.id_oeuvre, titre_oeuvre, livraison_oeuvre, depart_oeuvre, titre_expo
FROM oeuvres AS O
INNER JOIN composer AS C ON O.id_oeuvre = C.id_oeuvre
INNER JOIN expositions AS E ON C.id_expo = E.id_expo
WHERE (ISNULL(livraison_oeuvre))
AND (depart_oeuvre IS NOT NULL);

-- Sélectionner les emplacements des oeuvres liées à une exposition
SELECT E.num_emp, titre_oeuvre, titre_expo
FROM emplacements AS E
INNER JOIN composer AS C ON E.num_emp = C.num_emp
INNER JOIN oeuvres AS O ON C.id_oeuvre = O.id_oeuvre
INNER JOIN expositions AS EX ON C.id_expo = EX.id_expo
WHERE EX.id_expo = 1;

-- Même requête pour la seconde expo où l'on réutilise "Le Loukoum à la Pistache" à l'emplacement 2-01 au lieu de 1-04
SELECT E.num_emp, titre_oeuvre, titre_expo
FROM emplacements AS E
INNER JOIN composer AS C ON E.num_emp = C.num_emp
INNER JOIN oeuvres AS O ON C.id_oeuvre = O.id_oeuvre
INNER JOIN expositions AS EX ON C.id_expo = EX.id_expo
WHERE EX.id_expo = 2;

-- Requête pour lister les emplacements libres liés à une expo
SELECT num_emp
FROM emplacements
WHERE num_emp NOT IN (
  SELECT E.num_emp
  FROM emplacements AS E
  INNER JOIN composer AS C ON E.num_emp = C.num_emp
  INNER JOIN oeuvres AS O ON C.id_oeuvre = O.id_oeuvre
  INNER JOIN expositions AS EX ON C.id_expo = EX.id_expo
  WHERE EX.id_expo = 1
);

-- Liste des oeuvres du jeu de piste d'une expo
SELECT titre_expo, titre_oeuvre
FROM oeuvres AS O
INNER JOIN composer AS C ON O.id_oeuvre = C.id_oeuvre
INNER JOIN expositions AS E ON C.id_expo = E.id_expo
WHERE jeu_de_piste IS TRUE AND E.id_expo = 1
ORDER BY titre_oeuvre;

-- Nb de clicks par oeuvres par expo
SELECT titre_expo, titre_oeuvre, click_oeuvre
FROM oeuvres AS O
INNER JOIN composer AS C ON O.id_oeuvre = C.id_oeuvre
INNER JOIN expositions AS E ON C.id_expo = E.id_expo
WHERE E.id_expo = 1
ORDER BY click_oeuvre DESC;

-- Nb de clicks total par expo
SELECT titre_expo,
SUM(click_oeuvre) AS nb_clicks,
COUNT(O.id_oeuvre) AS nb_oeuvres,
ROUND(SUM(click_oeuvre) / COUNT(O.id_oeuvre), 2) AS nb_clicks_moyen_par_oeuvre
FROM oeuvres AS O
INNER JOIN composer AS C ON O.id_oeuvre = C.id_oeuvre
INNER JOIN expositions AS E ON C.id_expo = E.id_expo
GROUP BY titre_expo
ORDER BY sum(click_oeuvre) DESC;

-- Nb de clicks par expo des oeuvres qui reviennent plusieurs fois
-- SOUS REQUETE AVEC VARIABLE : https://oncletom.io/2007/utilisation-variables-mysql/
SET @r1 = (SELECT id_oeuvre
FROM composer AS C
GROUP BY id_oeuvre
HAVING COUNT(id_oeuvre) > 1);

SELECT titre_expo, titre_oeuvre, click_oeuvre
FROM oeuvres AS O
INNER JOIN composer AS C ON O.id_oeuvre = C.id_oeuvre
INNER JOIN expositions AS E ON C.id_expo = E.id_expo
WHERE O.id_oeuvre IN (@r1)
ORDER BY titre_oeuvre, titre_expo;

-- Nb de clicks total par oeuvre  pour les oeuvres  présentent dans au moins 2 expos
SELECT titre_oeuvre,
SUM(click_oeuvre) AS total_clicks,
COUNT(id_expo) AS nb_expo
FROM oeuvres AS O
INNER JOIN composer AS C ON O.id_oeuvre = C.id_oeuvre
GROUP BY O.id_oeuvre
HAVING COUNT(id_expo) > 1
ORDER BY sum(click_oeuvre) DESC;

-- Sélectionner les dimensions d'une oeuvre
SELECT titre_oeuvre, O.id_oeuvre, T.cat_type, DD.longueur_pic, DD.hauteur_pic, DDD.longueur_tri, DDD.largeur_tri, DDD.hauteur_tri
FROM oeuvres AS O
INNER JOIN avoir AS AV ON O.id_oeuvre = AV.id_oeuvre
INNER JOIN types AS T ON AV.id_type = T.id_type
INNER JOIN deux_dimensions AS DD ON AV.id_pic = DD.id_pic
INNER JOIN trois_dimensions AS DDD ON AV.id_tri = DDD.id_tri;

-- Sélectionner les dimensions d'un emplacement
SELECT num_emp, longueur_emp, largeur_emp, hauteur_emp
FROM emplacements
WHERE num_emp = '1-01';

-- Liste des oeuvres qui rentrent dans un emplacement spécifique (valeur en dur)
SELECT titre_oeuvre, O.id_oeuvre, T.cat_type, DD.longueur_pic, DD.hauteur_pic, DDD.longueur_tri, DDD.largeur_tri, DDD.hauteur_tri
FROM oeuvres AS O
INNER JOIN avoir AS AV ON O.id_oeuvre = AV.id_oeuvre
INNER JOIN types AS T ON AV.id_type = T.id_type
INNER JOIN deux_dimensions AS DD ON AV.id_pic = DD.id_pic
INNER JOIN trois_dimensions AS DDD ON AV.id_tri = DDD.id_tri
WHERE longueur_tri <= 200 AND largeur_tri <= 250 AND hauteur_tri <= 300
AND longueur_pic <= 200 AND hauteur_pic <= 300
ORDER BY id_oeuvre;

-- Sélectionner les oeuvres qui ne rentrent pas dans l'emplacement choisi
SELECT titre_oeuvre, titre_expo
FROM oeuvres AS O
INNER JOIN composer AS C ON O.id_oeuvre = C.id_oeuvre
INNER JOIN emplacements AS EMP ON C.num_emp = EMP.num_emp
INNER JOIN expositions AS EXP ON C.id_expo = EXP.id_expo
INNER JOIN avoir AS AV ON O.id_oeuvre = AV.id_oeuvre
INNER JOIN types AS T ON AV.id_type = T.id_type
INNER JOIN deux_dimensions AS DD ON AV.id_pic = DD.id_pic
INNER JOIN trois_dimensions AS DDD ON AV.id_tri = DDD.id_tri
WHERE (cat_type <> '0D')
AND (longueur_tri > 200 OR largeur_tri > 250 OR hauteur_tri > 300)
OR (longueur_pic > 200 OR hauteur_pic > 300);

-- Insérer les oeuvres dans la base de données (sans le jeu de piste)
INSERT INTO composer (livraison_oeuvre, id_oeuvre, id_expo, num_emp)
VALUES ('2019-01-01', 1, 1, '1-01');

-- Séléctionner le programme d'une expo
SELECT EMP.num_emp, O.titre_oeuvre, livraison_oeuvre
FROM oeuvres AS O
INNER JOIN composer AS C ON O.id_oeuvre = C.id_oeuvre
INNER JOIN expositions AS E ON C.id_expo = E.id_expo
INNER JOIN emplacements AS EMP ON C.num_emp = EMP.num_emp
WHERE E.id_expo = 6;

-- TODO --
-- Supprimer une expo
