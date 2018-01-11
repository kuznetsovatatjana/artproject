<?php
	require("vpconfig.php");
	require("functions.php");
	//echo $serverHost;
	
	//kui on sisseloginud, siis pealehele
	if(isset($_SESSION["userId"])){
		header("Location: main.php");
		exit();
	}
	$signupNickName = "";
	$signupFirstName = "";
	$signupFamilyName = "";
	$signupEmail = "";
	$signupPassword = "";
	
	$loginEmail = "";
	$loginPassword = "";
	
	$signupNickNameError = "";
	$signupFirstNameError = "";
	$signupFamilyNameError = "";
	$signupEmailError = "";
	$signupPasswordError = "";
	
	$loginEmailError ="";
	$loginPasswordError = "";
	
	$notice = "";
	
	//kas klõpsati sisselogimise nuppu
	if(isset($_POST["signinButton"])){
	
	//kas on kasutajanimi sisestatud
	if (isset ($_POST["loginEmail"])){
		if (empty ($_POST["loginEmail"])){
			$loginEmailError ="NB! Sisselogimiseks on vajalik kasutajatunnus (e-posti aadress)!";
		} else {
			$loginEmail = $_POST["loginEmail"];
		}
	}
	
	if(!empty($loginEmail) and !empty($_POST["loginPassword"])){
		//echo "Logime sisse!";
		$notice = signIn($loginEmail, $_POST["loginPassword"]);
	}
	
	}//kas sisselogimine lõppeb


//////////////////////////////////////////////////////////////////////////////////////////////////////////////	
	//kas luuakse uut kasutajat, vajutati nuppu
	if(isset($_POST["signupButton"])){
	
	if (isset ($_POST["signupNickName"])){
		if (empty($_POST["signupFirstName"])){
			$signupFirstNameError ="NB! Väli on kohustuslik!";
		} else {
			$signupFirstName = test_input($_POST["signupNickName"]);
		}
	}
	
	//kontrollime, kas kirjutati eesnimi
	if (isset ($_POST["signupFirstName"])){
		if (empty($_POST["signupFirstName"])){
			$signupFirstNameError ="NB! Väli on kohustuslik!";
		} else {
			$signupFirstName = test_input($_POST["signupFirstName"]);
		}
	}
	
	//kontrollime, kas kirjutati perekonnanimi
	if (isset ($_POST["signupFamilyName"])){
		if (empty($_POST["signupFamilyName"])){
			$signupFamilyNameError ="NB! Väli on kohustuslik!";
		} else {
			$signupFamilyName = test_input($_POST["signupFamilyName"]);
		}
	}

	
	//kontrollime, kas kirjutati kasutajanimeks email
	if (isset ($_POST["signupEmail"])){
		if (empty ($_POST["signupEmail"])){
			$signupEmailError ="NB! Väli on kohustuslik!";
		} else {
			$signupEmail = test_input($_POST["signupEmail"]);
						
			$signupEmail = filter_var($signupEmail, FILTER_SANITIZE_EMAIL);
			$signupEmail = filter_var($signupEmail, FILTER_VALIDATE_EMAIL);
		}
	}
	
	if (isset ($_POST["signupPassword"])){
		if (empty ($_POST["signupPassword"])){
			$signupPasswordError = "NB! Väli on kohustuslik!";
		} else {
			//polnud tühi
			if (strlen($_POST["signupPassword"]) < 8){
				$signupPasswordError = "NB! Liiga lühike salasõna, vaja vähemalt 8 tähemärki!";
			}
		}
	}
	
	
	//UUE KASUTAJA ANDMEBAASI KIRJUTAMINE, kui kõik on olemas	
	if (empty($signupEmailError) and empty($signupPasswordError) and empty($signupNickNameError) and empty($signupFirstNameError) and empty($signupFamilyNameError)){
		echo "Hakkan salvestama!";
		//krüpteerin parooli
		$signupPassword = hash("sha512", $_POST["signupPassword"]);
		
		signUp($signupNickName, $signupEmail, $signupPassword, $signupFirstName, $signupFamilyName);
		
	}
	
	}//uue kasutaja loomise lõpp
	
	
?>
<!DOCTYPE html>
<html lang="et">
<head>
	<meta charset="utf-8">
	<title>Sisselogimine või uue kasutaja loomine</title>
</head>
<body>
	<h1>Logi sisse!</h1>
	
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
		<label>Kasutajanimi (E-post): </label>
		<input name="loginEmail" type="email" value="<?php echo $loginEmail; ?>"><span><?php echo $loginEmailError; ?></span>
		<br><br>
		<input name="loginPassword" placeholder="Salasõna" type="password"><span></span>
		<br><br>
		<input name="signinButton" type="submit" value="Logi sisse"><span><?php echo $notice; ?></span>
	</form>
	
	<h1>Loo kasutaja</h1>
	
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
		<label>E-post</label>
		<input name="signupEmail" type="email" value="<?php echo $signupEmail; ?>">
		<span><?php echo $signupEmailError; ?></span>
		<br>
		<label>Password</label>
		<input name="signupPassword" placeholder="Salasõna" type="password">
		<span><?php echo $signupPasswordError; ?></span>
		<br>
		<label>Kasutajanimi</label>
		<input name="signupNickNameName" type="text" value="<?php echo $signupNickName; ?>">
		<span><?php echo $signupNickNameError; ?></span>
		<br>
		<label>Eesnimi </label>
		<input name="signupFirstName" type="text" value="<?php echo $signupFirstName; ?>">
		<span><?php echo $signupFirstNameError; ?></span>
		<br>
		<label>Perekonnanimi </label>
		<input name="signupFamilyName" type="text" value="<?php echo $signupFamilyName; ?>">
		<span><?php echo $signupFamilyNameError; ?></span>
		<br>	
		<br>
		<input name="signupButton" type="submit" value="Loo kasutaja">
	</form>
	<?php
	require("footer.php");	
	?>
</body>
</html>