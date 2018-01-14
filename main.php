<?php
	//et pääseks ligi sessioonile ja funktsioonidele
	require("functions.php");
	require("vpconfig.php");
	
	//kui pole sisseloginud, liigume login lehele
	if(!isset($_SESSION["userId"])){
		header("Location: login.php");
		exit();
	}
	
	if(isset($_GET["logout"])){
		session_destroy();
		header("Location: login.php");
		exit();
	}
	
	//if upload button is pressed
	
	
  // Create database connection

  // Initialize message variable
  $msg = "";
  $db = "if17_tanjak";
  $db = mysqli_connect("$serverHost", "$serverUsername", "$serverPassword", "$db");

  // If upload button is clicked ...
  if (isset($_POST['upload'])) {
  	// Get image name
  	$image = $_FILES['image']['name'];
  	// Get text
  	$text = mysqli_real_escape_string($db, $_POST['text']);

  	// image file directory
  	$target = "pictures/".basename($image);

  	$sql = "INSERT INTO pr_art_upload (image,text) VALUES ('$image', '$text')";
  	// execute query
  	mysqli_query($db, $sql);

  	if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
  		$msg = "Image uploaded successfully";
  	}else{
  		$msg = "Failed to upload image";
  	}
  }
	
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>
		Art Gallery
	</title>
</head>
<body>
<p><a href="?logout=1">Logi valja!</a></p>

<div id="content">
	<form method="post" action="main.php" enctype="multipart/form-data">
		Select image to upload:
		<input type="hidden" name="size" value="10000">
		
		<input type="file" name="image">

		<textarea name="text" cols="40" rows="4"></textarea>

		<input type="submit" value="upload" name="upload">
	
	</form>
</div>	



</body>
</html>