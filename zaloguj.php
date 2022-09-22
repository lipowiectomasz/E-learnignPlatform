<?php
	require 'dbInfo.php';
	include 'getSysInfo.php';

	$user = htmlentities ($_POST['user'], ENT_QUOTES, "UTF-8"); // rozbrojenie potencjalnej bomby w zmiennej $user
	$pass = htmlentities ($_POST['pass'], ENT_QUOTES, "UTF-8"); // rozbrojenie potencjalnej bomby w zmiennej $pass
	
	$polaczenie = mysqli_connect($dbhost, $dbuser, $dbpassword, $dbname);
	mysqli_query($polaczenie, "SET NAMES 'utf8'");

	$query = "SELECT id, login, pass, userType FROM users WHERE login='$user';";
	$rezultat = mysqli_query($polaczenie, $query) or die ("Problem with searching of user!");
	
	$rekord = mysqli_fetch_array($rezultat); 
	if(!$rekord) //Jeśli brak, to nie ma użytkownika o podanym loginie
	{
		$status = 0;
		mysqli_close($polaczenie);
		$location = "Location: index.php?status=$status";
		header($location);
	}
	else
	{ // jeśli $rekord istnieje
		
		if($rekord['pass']==$pass) // czy hasło zgadza się z BD
		{ 
			echo "Logowanie Ok. User: {$rekord['login']}. Hasło: {$rekord['pass']}";

			session_start();
			
			$_SESSION ['loggedin'] = true;
			$_SESSION ['user'] = $user;
			$_SESSION ['idUser'] = $rekord['id'];

			
			$sys = new getSysInfo();
			$sys->checkSys();
			$ip = $_SERVER['REMOTE_ADDR'];
			$idUser = $rekord['id'];
			$przegladarka = $sys->browser;
			$system = $sys->OSystem;

			$godzina = date('H:i:s', time());
			$data = date ('Y-m-d', time()); 
			$correct = "TAK";
			
			$logZapis = "INSERT INTO logi (idUser, adresIp, data, godzina, przegladarka, systemOp, poprawnosc) VALUES ('$idUser', '$ip', '$data', '$godzina', '$przegladarka', '$system', '$correct');";

			
			$zapiszLogowanie = mysqli_query($polaczenie, $logZapis) or die ("Blad w zapisie logowania");
			mysqli_close($polaczenie);
			
			$type = $rekord['userType'];
			if($type == 0){
				$location = "Location: panelAdmina.php";
			}
			elseif($type == 1){
				$location = "Location: panelSzkoleniowiec.php";
			}
			elseif($type == 2){
				$location = "Location: panelKursanta.php";
			}
			header($location);
		}
		else 
		{	
			session_start();
			$godzina = date('H:i:s', time());
			$data = date ('Y-m-d', time()); 
			$correct = "NIE";
			
			
			$sys = new getSysInfo();
			$sys->checkSys();
			$ip = $_SERVER['REMOTE_ADDR'];
			$idUser = $rekord['id'];
			$przegladarka = $sys->browser;
			$system = $sys->OSystem;

			$_SESSION ['loggedin'] = false;
			
			if(!IsSet($_SESSION ['try'])){
				$_SESSION ['try']=0;
			}

			$try = $_SESSION ['try'];
			$try++;
			$_SESSION ['try'] = $try;

			echo "DANE niepoprawnego logowania: ".$idUser." ".$data." ".$godzina." ".$correct." ".$ip." ".$przegladarka." ".$system." </br>";
			$logZapis = "INSERT INTO logi (idUser, adresIp, data, godzina, przegladarka, systemOp, poprawnosc) VALUES ('$idUser', '$ip', '$data', '$godzina', '$przegladarka', '$system', '$correct');";
			$zapiszLogowanie = mysqli_query($polaczenie, $logZapis) or die ("Blad w zapisie niepotwierdzonego logowania");
			echo "Bledne dane logowania!"; // UWAGA nie wyświetlamy takich podpowiedzi dla hakerów
			mysqli_close($polaczenie);

			$status = 1;
			$location = "Location: index.php?status=$status";
			header($location);
		}
	}
	
	
?>
