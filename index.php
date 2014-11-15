<?php
header('Content-Type: text/html; charset=utf-8');
date_default_timezone_set('Europe/Berlin');
include('config.php');
$db = new mysqli($c_dbserver, $c_dbuser, $c_dbpw);
if($db->connect_error) die('Datenbankverbindung nicht möglich. ');
$db->select_db($c_dbdb);
if( isset($_POST["add"]) and ($c_addpoll==true)) {
	$add_text=$db->real_escape_string($_POST["text"]);
	$query_text = "INSERT INTO `rwth1`.`polls` (`id`, `NAME`) VALUES (NULL, '".$add_text."')";
	$db->query($query_text);
}
?>
<!doctype html>
<html>
<head>
<title>Direktfeedback/Umfragen für GGI1 WS14/15</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
</head>
<body>
<h1>Direktfeedback/Umfragen</h1>
<ul>
<?php
if ($result = $db->query("SELECT id,name,created FROM polls")) {
	while($row = $result->fetch_assoc()) {
		echo('<li><a href="poll.php?id='.$row["id"].'">'.$row["name"].'</a> ('.date("D M j G:i",strtotime($row["created"])).') <a href="chart.php?id='.$row["id"].'">Graph</a> </li>');
	}

	$result->close();
}



?>
<li><a href="poll_add.php">Umfrage hinzufügen</a></li>
</ul>
</body>
</html>
<?php
$db->close();
?>
