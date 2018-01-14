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
  
  //laadib kqik pildid
  
  $arts = getAllArt();
	
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

	<?php
	$html = "<table>";
		
		$html .= "<tr>";
			$html .= "<th>art_name</th>";
			$html .= "<th>image</th>";
			$html .= "<th>text</th>";
			$html .= "<th>email</th>";
			$html .= "<th>timestamp</th>";
		$html .= "</tr>";
		
		// iga liikme kohta massiivis
		foreach ($arts as $p) {
		
		$html .= "<tr>";
			$html .= "<td>".$p->art_name."</td>";
			$html .= "<td><img width='50%' height='50%' src='pictures/".$p->image."'/></td>";
			$html .= "<td>".$p->text."</td>";
			$html .= "<td>".$p->email."</td>";
			$html .= "<td>".$p->timestamp."</td>";
		$html .= "</tr>";
		
		}
	
	$html .= "</table>";
	echo $html;
	?>


	<div id="content">
	</div>

<p><a href="?logout=1">Logi valja!</a></p>

<div id="content">
	<form method="post" action="main.php" enctype="multipart/form-data">
		Select image to upload:
		
		<input name="art_name" class="text" type="art_name">
		
		<input type="file" name="image">

		<textarea name="text" cols="40" rows="4"></textarea>

		<input type="submit" value="upload" name="upload">
	
	</form>
</div>	



</body>
</html>