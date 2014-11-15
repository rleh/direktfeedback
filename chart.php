<?php
header("Content-Type: text/html; charset=utf-8");
include('config.php');
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
?>
<!doctype html>
<html>
<head>
<title>Direktfeedback/Umfrage - <?php echo($poll_title) ?></title>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<script src="Chart.js/Chart.js"></script>
</head>
<body>
<h4><?php echo($poll_title) ?></h4>
<canvas id="myChart"></canvas>
<?php
$labels = "";
$data = "";
if ($result = $db->query("SELECT `count`,`text` FROM `choices` WHERE `poll_id`=".$poll_id)) {
	while($row = $result->fetch_assoc()) {
		$labels.='"'.$row[text].'",';
		$data.=$row["count"].',';
	}
	$result->close();
}
$labels = substr($labels, 0, -1);
$data = substr($data, 0 , -1);
?>
<script type="text/javascript">
	var barChartData = {
		labels : [<?php echo($labels); ?>],
		datasets : [
			{
				fillColor : "rgba(151,187,205,0.5)",
				strokeColor : "rgba(151,187,205,0.8)",
				highlightFill : "rgba(151,187,205,0.75)",
				highlightStroke : "rgba(151,187,205,1)",
				data : [<?php echo($data); ?>]
			}
		]

	}
	window.onload = function(){
		var ctx = document.getElementById("myChart").getContext("2d");
		window.myBar = new Chart(ctx).Bar(barChartData, {
			responsive: false,
			maintainAspectRatio: true,
			animation: false,
			labelFontSize: 24
		});
		setTimeout("location.reload(true);",5000);
	}
</script>
<a href="index.php"> Zurück </a>
</body>
</html>
<?php
$db->close();
?>
