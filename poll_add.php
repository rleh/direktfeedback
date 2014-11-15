<?php
header("Content-Type: text/html; charset=utf-8");
include('config.php');
if(!$c_addpoll) die("Hinzufügen von Umfragen nicht erlaubt");
$db = new mysqli($c_dbserver, $c_dbuser, $c_dbpw);
if($db->connect_error) die('Datenbankverbindung nicht möglich. ');
$db->select_db($c_dbdb);
date_default_timezone_set('Europe/Berlin');
?>
<!doctype html>
<html>
<head>
<title>Direktfeedback/Umfrage hinzufügen </title>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
</head>
<body>
<h3>Umfrage hinzufügen:</h3>

<form action="index.php" method="post">
 <p>Neue Umfrage: <input type="text" name="text" /></p>
 <input type="hidden" name="add" />
 <p><input type="submit" value="Hinzufügen" /></p>
</form>
<br />
<a href="index.php"> Zurück </a>
</body>
</html>
<?php
$db->close();
?>
