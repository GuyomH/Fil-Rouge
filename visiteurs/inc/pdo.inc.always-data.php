<?php
const LOGIN = "166984";
const PWD = "Gptovc01!";
const DBNAME = "guillaume-henaud_grand_angle";
$dsn = 'mysql:host=mysql-guillaume-henaud.alwaysdata.net;dbname=' . DBNAME . ';charset=utf8';
try
{
  $db = new PDO($dsn, LOGIN, PWD);
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
}
catch (Exception $e)
{
  die('Erreur : ' . $e->getMessage());
}
?>
