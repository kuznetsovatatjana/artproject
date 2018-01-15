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
	
	//Oma pilte laadimine
	function saveArt($art_url, $art_name) {
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		
		$stmt = $mysqli->prepare("INSERT INTO pr_art_upload (art_url, art_name, email) VALUE (?, ?, ?)");
		echo $mysqli->error;
		
		$stmt->bind_param("sss", $art_url, $art_name, $_SESSION["userEmail"]);
		if ( $stmt->execute() ) {
		} else {
			echo "ERROR ".$stmt->error;
		}		
	}
		
	//Naitame viimased 2 pilte
	function getAllArt(){

		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		
		$stmt = $mysqli->prepare("
		SELECT id,art_name, image, timestamp
		FROM pr_art_upload
		ORDER BY id
		DESC LIMIT 2
		");
		$stmt->bind_result($id,$art_name, $image, $timestamp);
		$stmt->execute();
		
		$results = array();
		
		while($stmt->fetch()) {
			
			$arts = new StdClass();
			$arts->id = $id;
			$arts->art_name = $art_name;
			$arts->image = $image;
			$arts->timestamp = $timestamp;
			
			array_push($results, $arts);
			
		}
		return $results;	
	}

	//sisestuse kontrollimine
	function test_input($data){
		$data = trim($data);//eemaldab lõpust tühiku, tab vms
		$data = stripslashes($data);//eemaldab "\"
		$data = htmlspecialchars($data);//eemaldab keelatud märgid
		return $data;
	}
	
	//Yhe postituse näitamine
	function getsingleId($show_id){
			
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
			
	$stmt = $mysqli->prepare("
	SELECT art_name, image, text,email
	FROM pr_art_upload 
	WHERE id = ?"); //võttab see sama ID mis klqpsutakse
			
	$stmt->bind_param("i", $show_id);
	$stmt->bind_result($art_name, $image, $text, $email);
	$stmt->execute();
	$singleId = new Stdclass();
			
			if($stmt->fetch()){
				$singleId->art_name = $art_name;
				$singleId->image = $image;
				$singleId->text = $text;
				$singleId->email = $email;
			}else{
				header("Location: comment.php"); //kuhu saadab
				exit();
			}
			$stmt->close();
			return $singleId;
	}
	
	//kqik postitused
	function getAllUserArt(){

		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		
		$stmt = $mysqli->prepare("
		SELECT id, art_name, image,text,email, timestamp
		FROM pr_art_upload
		");
		$stmt->bind_result($id,$art_name, $image, $text,$email, $timestamp);
		$stmt->execute();
		
		$results = array();
		
		while($stmt->fetch()) {
			
			$arts = new StdClass();
			$arts->id = $id;
			$arts->art_name = $art_name;
			$arts->image = $image;
			$arts->text = $text;
			$arts->email = $email;
			$arts->timestamp = $timestamp;
			
			array_push($results, $arts);
			
		}
		return $results;	
	}
	 
	 //NAITAB UHE KASUTAJA INFOT
	function oneUserInfo(){
		
		$mysqli = new mysqli($GLOBALS["serverHost"], 
		$GLOBALS["serverUsername"], 
		$GLOBALS["serverPassword"], 
		$GLOBALS["database"]);
		
		$stmt = $mysqli->prepare("
		SELECT art_name, image,text,email
		FROM pr_art_upload
		WHERE email = ?
		");
		
		$stmt->bind_param("s", $_SESSION["userEmail"]);
		$stmt->bind_result( $art_name, $image,$text,$email);
		$stmt->execute();
		$results = array();
		
		while ($stmt->fetch()) {
			$userinf = new StdClass();
			$userinf->art_name = $art_name;
			$userinf->image = $image;
			$userinf->text = $text;
			$userinf->email = $email;
			array_push($results, $userinf);	
		}
		return $results;
	}
?>
