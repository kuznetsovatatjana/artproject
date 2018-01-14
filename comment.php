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
	

	//kommentaaride saamine
	function send_comment($comment){
	
		$mysqli = new mysqli($GLOBALS["serverHost"], 
		$GLOBALS["serverUsername"], 
		$GLOBALS["serverPassword"], 
		$GLOBALS["database"]);
		
		$comment = $_POST["comment"];
		
		$stmt = $mysqli ->prepare("INSERT INTO pr_comments ( id_post, comment, email) VALUE(?, ?, ?)");
		echo $mysqli->error;
		$stmt->bind_param("iss", $_GET["id"], $comment, $_SESSION["userEmail"]);
	
		if($stmt->execute() ) {			
		}
	}
	
	//Kommentaaride naitamine
	function show_comment(){
		
		$mysqli = new mysqli($GLOBALS["serverHost"], 
		$GLOBALS["serverUsername"], 
		$GLOBALS["serverPassword"], 
		$GLOBALS["database"]);
		
		$stmt = $mysqli->prepare("
		SELECT comment, email
		FROM pr_comments
		WHERE id_post = ?
		");
		
		$stmt->bind_param("s", $_GET["id"]);
		$stmt->bind_result($comment, $email );
		$stmt->execute();
		$results = array();
		
		while ($stmt->fetch()) {
			$human = new StdClass();
			$human->comment = $comment;
			$human->email = $email;
			array_push($results, $human);	
		}
		return $results;
	}
	
	//kommentaaride salvestaime
	if (isset ($_POST["comment"]) &&
		!empty ($_POST["comment"])
		)
		
	//kommentaaride saamine
	{
	send_comment($_GET["id"],$_POST["comment"],$_SESSION["userEmail"]);
	}
	
	$people = show_comment();
?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>
		Art Gallery
	</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>

<ul>
  <li><a class="active" href="main.php">Home</a></li>
  <li><a href="myposts.php">Minu postitused</a></li>
  <li><a href="allposts.php">Postitused</a></li>
  <li style="float:right"><a href="?logout=1">Logi valja</a></li>
</ul>

<body>

<div class="content">
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


	<form method="POST">

	<input name="comment" class="text" placeholder="Jata kommentaar" required>	
	<br><input type="submit" value="Saada"></br>

	</form>

	
<?php 
$html1 = "<table>";
	foreach ($people as $p) {
	$html1 .= "<tr>";
		$html1 .= "<td>".$p->email."</td>";
		$html1 .= "<td>".$p->comment."</a></td>";
	$html1 .= "</tr>";
	}
$html1 .= "</table>";
echo $html1
?>
	
</div>
</body>
</html>