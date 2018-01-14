<?php
	//et pääseks ligi sessioonile ja funktsioonidele
	require("functions.php");
	
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

	
	<form action="upload.php" method="post" enctype="multipart/form-data">
		Select image to upload:
		<input type="file" name="fileToUpload" id="fileToUpload">
		<input type="submit" value="Upload Image" name="submit">
	</form>



</body>
</html>