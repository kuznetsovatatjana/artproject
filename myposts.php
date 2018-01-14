<?php
	//et pääseks ligi sessioonile ja funktsioonidele
	require("functions.php");
	require("vpconfig.php");
	
	//kui pole sisseloginud, liigume login lehele
	if(!isset($_SESSION["userId"])){
		header("Location: login.php");
		exit();
	}
	
	//Valjumine veebist
	if(isset($_GET["logout"])){
		session_destroy();
		header("Location: login.php");
		exit();
	}
	
  //PILTI LAADIMINE
  
  $msg = "";
  $db = "if17_tanjak";
  $db = mysqli_connect("$serverHost", "$serverUsername", "$serverPassword", "$db");
  
  //Kui vajutatakse nuppu
  if (isset($_POST['upload'])) {
  	// Piltide nimi
  	$image = $_FILES['image']['name'];
  	//tekst
  	$text = mysqli_real_escape_string($db, $_POST['text']);
	$art_name = mysqli_real_escape_string($db, $_POST['art_name']);
	$email = $_SESSION["userEmail"]; // vottab just sama sessionist kasutajat
  	// image file directory
  	$target = "pictures/".basename($image);
	
  	$sql = "INSERT INTO pr_art_upload (art_name,image,text,email) VALUES ('$art_name','$image', '$text','$email')";
  	mysqli_query($db, $sql);

	//Kas pilt laaditud ja kas kqik on korras
  	if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
  		$msg = "Image uploaded successfully";
  	}else{
  		$msg = "Failed to upload image";
  	}
  }

	
	$people = kasutajainfo();
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>
		Minu postitused
	</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

<ul>
  <li><a class="active" href="main.php">Home</a></li>
  <li><a href="myposts.php">Minu postitused</a></li>
  <li><a href="allposts.php">Postitused</a></li>
  <li style="float:right"><a href="?logout=1">Logi valja</a></li>
</ul>

<div class="content">

<h1>Sinu postitused:</h1>

<?php 
		$html = "<table>";
			
			$html .= "<tr>";
				$html .= "<th>Piltide nimed</th>";
				$html .= "<th>Piltid</th>";
				$html .= "<th>Kirjeldus</th>";
				$html .= "<th>Email</th>";
			$html .= "</tr>";
			
			foreach ($people as $p) {
			$html .= "<tr>";
				$html .= "<td>".$p->art_name."</td>";
				$html .= "<td><img width='50%' height='50%' src='pictures/".$p->image."'/></td>";
				$html .= "<td>".$p->text."</td>";
				$html .= "<td>".$p->email."</td>";
			$html .= "</tr>";
			}
			$html .= "</table>";
			
		echo $html
?>

<a href="upload.php"><button class="button button2">Laadi oma pilti</button></a>
</div>

</body>
</html>