<?php

	$database = "if17_tanjak";
	
	//alustame sessiooni
	session_start();
	
	//uue kasutaja registreerimine
	//uue kasutaja andmebaasi lisamine
	function signUp($signupEmail, $signupPassword, $signupNickName, $singupgender){
		
		$notice2 = "";
		//loome andmebaasiühenduse
		
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		//valmistame ette käsu andmebaasiserverile
		$stmt = $mysqli->prepare("INSERT INTO pr_user (email, password, nickname, gender) VALUES (?, ?, ?, ?)");
		echo $mysqli->error;
		$stmt->bind_param("ssss", $signupEmail, $signupPassword, $signupNickName, $singupgender);
		
		//$stmt->execute();
		if ($stmt->execute()){
			echo "\n Õnnestus!";
		} else {
			$notice2 = "Selline e-post on juba olemas !";
		}
		return $notice2;
		
		$stmt->close();
		$mysqli->close();
	}
	
	//loogimine sisse
		function login($email,$password) {
		
		$notice = "";
		$mysqli = new mysqli($GLOBALS["serverHost"],$GLOBALS["serverUsername"],$GLOBALS["serverPassword"],$GLOBALS["database"]);
		
		$stmt = $mysqli->prepare("
		SELECT user_id, email, password
		FROM pr_user
		WHERE email = ?
		");
		
		echo $mysqli->error;
		
		$stmt->bind_param("s", $email);
		$stmt->bind_result($id, $emailFromDb, $passwordFromDb);
		$stmt->execute();
		
		if($stmt->fetch()) {
			$hash = hash("sha512", $password);
		
		if ($hash == $passwordFromDb) {
			echo "Kasutaja $id logis sisse";
			$_SESSION["userId"] = $id;
			$_SESSION["userEmail"] = $emailFromDb;
			header("Location: main.php");
		} else {
			$notice = "Vale parool";
			}	
		} else {
			$notice = "Kasutajat e-posti aadressiga ".$email." ei leitud.";
		}
		
		return $notice;
	}
	
	
	//sisestuse kontrollimine
	function test_input($data){
		$data = trim($data);//eemaldab lõpust tühiku, tab vms
		$data = stripslashes($data);//eemaldab "\"
		$data = htmlspecialchars($data);//eemaldab keelatud märgid
		return $data;
	}
?>