<?php
	
	//Require
	require("functions.php");
	require("vpconfig.php");
	
	//Valja logimine
	if(isset($_GET["logout"])){
		session_destroy();
		header("Location: login.php");
		exit();
	}
	
	//Vottab yhe ID ja naitab temast infot
	$p = getsingleId($_GET["id"]);
?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>
		Art Gallery
	</title>
</head>

<p><a href="?logout=1">Logi valja!</a></p>
<p><a href="main.php">tagasi</a></p>

<?php 
$html = "<table>";
	$html .= "<tr>";
		$html .= "<td>".$p->art_name."</td>";
		$html .= "<td><img width='20%' height='20%' src='pictures/".$p->image."'/></td>";
		$html .= "<td>".$p->text."</a></td>";
		$html .= "<td>".$p->email."</a></td>";	
	$html .= "</tr>";
$html .= "</table>";
echo $html
?>

</html>