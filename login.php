<?php
	
	require("vpconfig.php");
	require("functions.php");
	//echo $serverHost;
	
	//kui on sisseloginud, siis pealehele
	if(isset($_SESSION["userId"])){
		header("Location: main.php");
		exit();
	}
	
	//Muutujad registreerimne
	$signupEmail = $signupPassword = $signupNickName = $singupgender = "";
	$signupEmailError = $signupPasswordError = $signupNickNameError = "";
	
	//Muutujad logiminie
	$loginEmail = $loginPassword = "" ;	
	$loginEmailError = $loginPasswordError = $notice = $notice2 = "";

	//registreerimine
	//E-post
	if (isset ($_POST["signupEmail"])) {
			if (empty($_POST["signupEmail"])) {
			$signupEmailError = "NB! Väli on kohustuslik!";
			} else {
			$signupEmail = $_POST ["signupEmail"];
			}
		}
	//parool
	if(isset ($_POST["signupPassword"])) {
		if (empty ($_POST["signupPassword"])) {
		$signupPasswordError = "NB! Väli on kohustuslik!";
		} else {
		if (strlen ($_POST["signupPassword"]) <5)
		$signupPasswordError = "Parool peab olema vähemalt 5 tähemärki!";
		}
	}
	//kasutajanimi
	if (isset ($_POST["signupNickName"])) {
		if (empty ($_POST["signupNickName"])) {
		$signupNickNameError = "NB! Väli on kohustuslik!";
		} else {
		if (strlen ($_POST["signupNickName"]) >20) {
		$signupNickNameError = "Kasutajanime maksimaalne pikkus on 20 tähemärki!";
		} else {
		$signupNickName = $_POST ["signupNickName"];
			}
		}
	}
	
	//Registreerimise lqpp ja salvestamine
	if ( $signupEmailError == "" AND
		$signupPasswordError == "" &&
		isset($_POST["signupEmail"]) &&
		isset($_POST["signupPassword"])
	)
	if (isset($_POST["signupEmail"])&&
		!empty($_POST["signupEmail"])
		)
	
	{
	$signupPassword = hash("sha512", $_POST["signupPassword"]);
	$notice2 = signUp($signupEmail, $signupPassword, $signupNickName, $_POST["singupgender"]);
	}
	
	//Sisselogimine
	//E-post logimine
	if (isset ($_POST["loginEmail"])) {
		if (empty ($_POST["loginEmail"])) {
		$loginEmailError = "See väli on kohustuslik!";
		} else {
		$loginEmail = $_POST ["loginEmail"];
		}
	}
	//parooli logimine
	if (isset ($_POST["loginPassword"])) {
		if (empty ($_POST["loginPassword"])) {
		$loginPasswordError = "See väli on kohustuslik!";
		} else {
		$loginPassword = $_POST ["loginPassword"];
		}
	}
	
	//logimise lqpp ja salvestamine
	if (isset ($_POST["loginEmail"]) &&
		isset ($_POST["loginPassword"])  &&
		!empty ($_POST["loginEmail"]) &&
		!empty ($_POST["loginPassword"])
		)
		
	{
	$notice = login($_POST["loginEmail"], $_POST["loginPassword"]); //notice näitab kas parool või email on vale
	}
?>
<!DOCTYPE html>
<html lang="et">
<head>
	<meta charset="utf-8">
	<title>Sisselogimine või uue kasutaja loomine</title>
</head>
<body>

	<!--LOGIMINE-->
	<h1>Logi sisse</h1>
		
		<form method="POST">
		
			<label>Sisselogimine</label>
			<?=$notice;?>
			<!--e-posti logimine-->
			<input name="loginEmail" type="loginEmail" class="text" placeholder="E-Post" value=<?=$loginEmail;?>>
			<br><?php echo $loginEmailError;?></br>
			
			<!--parooli logimine-->
			<label>Parool</label>
			<input name="loginPassword" type="password" class="text" placeholder="Parool">
			<br><?php echo $loginPasswordError;?>
			
			<!--loogimise nupp-->
			<input type="submit" value="Logi sisse">
		
		</form>
	
	<!--KASUTAJA REGISTREERIMINE-->
	<h1>Loo kasutaja</h1>

		<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
			<!--e-posti registreerimine-->
			<?php echo $notice2;?> <br>
			<label>E-post</label>
			<input name="signupEmail"  placeholder="e-post" type="email" value=<?=$signupEmail;?>>
			<span><?php echo $signupEmailError; ?></span>
			<br>
			
			<!--parooli registreerimine-->
			<label>Password</label>
			<input name="signupPassword" placeholder="Salasõna" type="password">
			<span><?php echo $signupPasswordError; ?></span>
			<br>
			
			<!--kasutaja nimi registreerimine-->
			<label>Kasutajanimi</label>
			<input name="signupNickName" type="signupNickName"  placeholder="Kasutajanimi" class="text" value=<?=$signupNickName;?>>
			<span><?php echo $signupNickNameError; ?></span>
			<br>
		
			<!--sugu registreerimine-->
			<label for="singupgender">Sugu</label>
			<select name = "singupgender"  id="singupgender" required>
			<option value="">Avamiseks vajuta</option>
			<option value="Male">Mees</option>
			<option value="Female">Naine</option>
			<option value="Other">Muu</option>
			</select>
		
			<!--registreerimise nupp-->
			<br><input  type="submit" value="Loo kasutaja"></br>
	
	</form>

</body>
</html>