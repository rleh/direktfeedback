<?php
header("Content-Type: text/html; charset=utf-8");
include('config.php');
$db = new mysqli($c_dbserver, $c_dbuser, $c_dbpw);
if($db->connect_error) die('Datenbankverbindung nicht möglich. ');
$db->select_db($c_dbdb);
date_default_timezone_set('Europe/Berlin');
$poll_id=$db->real_escape_string($_GET["id"]);
$success="";
if ($result = $db->query("SELECT name FROM polls WHERE id=".$poll_id." LIMIT 1")) {
	$row = $result->fetch_assoc();
	$poll_title = $row["name"];
	$result->close();
}
if(isset($_GET["choice_id"]) and !(isset($_COOKIE["direktfeedbacka_".$poll_id]))){
	$choice_id=$db->real_escape_string($_GET["choice_id"]);
	$result = $db->query("UPDATE choices SET count=count+1 WHERE id=".$choice_id);
	setcookie("direktfeedbacka_".$poll_id, 1, time()+60*60*24*30);
}else if(isset($_POST["add"]) and ($c_addchoice==true)) {
	$add_text=$db->real_escape_string($_POST["text"]);
	$add_pollid=$db->real_escape_string($_POST["add_poll"]);
	//$add_=$db->real_escape_string($_GET["id"]);
//	$db->query("INSERT INTO choices (text, poll_id) VALUES (".$add_text.", ".$add_pollid.")");
	$query_text = "INSERT INTO `rwth1`.`choices` (`id`, `text`, `poll_id`, `comment`, `count`, `created`) VALUES (NULL, '".$add_text."', '".$add_pollid."', '-', '0', CURRENT_TIMESTAMP);";
	$db->query($query_text);
	$success = "Antwortmöglichkeit ".$add_text." erfolgreich hinzugefügt.";
}
?>
<!doctype html>
<html>
<head>
<title>Direktfeedback/Umfrage - <?php echo($poll_title) ?></title>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
</head>
<body>
<h1><?php echo($poll_title) ?></h1>
<ul>
<?php
if ($result = $db->query("SELECT id,text,count FROM choices WHERE poll_id=".$poll_id)) {
	while($row = $result->fetch_assoc()) {
		echo('<li><a href="poll.php?id='.$poll_id.'&choice_id='.$row["id"].'">'.$row["text"].' ('.$row["count"].')</a></li>
');
	}
}
$result->close();
?>
<li><a href="choice_add.php?id=<?php echo($poll_id); ?>">Antwort hinzuf&uuml;gen</a></li>
</ul>
<?php echo($success); ?>
<br />
<a href="index.php"> Zurück </a>
</body>
</html>
<?php
$db->close();
?>
