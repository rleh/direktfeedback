<?php
header('Content-Type: text/html; charset=utf-8');
include('config.php');
if(!$c_addchoice) die("Hinzufügen von Antwortmöglichkeiten nicht erlaubt");
header("Content-Type: text/html; charset=utf-8");
$db = new mysqli($c_dbserver, $c_dbuser, $c_dbpw);
if($db->connect_error) die('Datenbankverbindung nicht möglich. ');
$db->select_db($c_dbdb);
date_default_timezone_set('Europe/Berlin');
$poll_id=$db->real_escape_string($_GET["id"]);
if ($result = $db->query("SELECT name FROM polls WHERE id=".$poll_id." LIMIT 1")) {
	$row = $result->fetch_assoc();
	$poll_title = $row["name"];
	$result->close();
}
if(isset($_GET["choice_id"])){
	$choice_id=$db->real_escape_string($_GET["choice_id"]);
	$result = $db->query("UPDATE choices SET count=count+1 WHERE id=".$choice_id);
}
?>
<!doctype html>
<html>
<head>
<title>Direktfeedback/Umfrage - <?php echo($poll_title) ?></title>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
</head>
<body>
<h3>Umfrage: "<?php echo($poll_title) ?>"</h3>

<form action="poll.php?id=<?php echo($poll_id); ?>" method="post">
 <p>Neue Antwortmöglichkeit: <input type="text" name="text" /></p>
 <input type="hidden" name="add" />
 <input type="hidden" name="add_poll" value="<?php echo($poll_id); ?>" />
 <p><input type="submit" value="Hinzufügen" /></p>
</form>
<br />
<a href="poll.php?id=<?php echo($poll_id); ?>"> Zurück </a>
</body>
</html>
<?php
$db->close();
?>
