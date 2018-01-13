<?php

	$database = "if17_tanjak";
	
	//alustame sessiooni
	session_start();
	
	//uue kasutaja registreerimine
	//uue kasutaja andmebaasi lisamine
	function signUp($signupEmail, $signupPassword, $signupNickName, $singupgender, $signupartgenre){
		//loome andmebaasiühenduse
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		//valmistame ette käsu andmebaasiserverile
		$stmt = $mysqli->prepare("INSERT INTO pr_user (email, password, nickname, gender, artgenre) VALUES (?, ?, ?, ?, ?)");
		echo $mysqli->error;
		$stmt->bind_param("sssss", $signupEmail, $signupPassword, $signupNickName, $singupgender, $signupartgenre);
		//$stmt->execute();
		if ($stmt->execute()){
			echo "\n Õnnestus!";
		} else {
			echo "\n Tekkis viga : " .$stmt->error;
		}
		$stmt->close();
		$mysqli->close();
	}
	
	//sisestuse kontrollimine
	function test_input($data){
		$data = trim($data);//eemaldab lõpust tühiku, tab vms
		$data = stripslashes($data);//eemaldab "\"
		$data = htmlspecialchars($data);//eemaldab keelatud märgid
		return $data;
	}
?>