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
  
  //laadib kqik pildid tabel
  $people = getAllArt();

	
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
<body>

<ul>
  <li><a class="active" href="main.php">Home</a></li>
  <li><a href="#news">Postitused</a></li>
  <li><a href="#contact">Minu postitused</a></li>
  <li style="float:right"><a href="?logout=1">Logi valja</a></li>
</ul>

<div class="content">

	<form method="post" action="main.php" enctype="multipart/form-data">
		<br>
		<h1>Laadi oma pilt</h1>
		<h2>Pilti nimi</h2>
		<div>
		<input name="art_name" class="text" type="art_name" required>
		</div>
		<br>
		<h2>Vali pilt</h2>
		<div>
		<input type="file" name="image">
		</div>
		<br>
		<h2>Luhike kirjeldus</h2>
		<div>
		<textarea name="text" cols="40" rows="4" required></textarea>
		</div>
		<br>
		<div>
		<input type="submit" value="upload" name="upload">
		</div>
	</form>
</div>	

</body>
</html>