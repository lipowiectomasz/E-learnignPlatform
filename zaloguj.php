<?php
	require dirname(__DIR__) . '/PlatformaELearningowa/vendor/autoload.php';
	use databaseOperator\operator;
	$base = new operator();
	include 'getSysInfo.php';

	$user = htmlentities ($_POST['user'], ENT_QUOTES, "UTF-8"); // rozbrojenie potencjalnej bomby w zmiennej $user
	$pass = htmlentities ($_POST['pass'], ENT_QUOTES, "UTF-8"); // rozbrojenie potencjalnej bomby w zmiennej $pass

	$query = "SELECT id, login, pass, userType FROM users WHERE login='$user';";
	$rekord = $base->selectRowOperation($query);
	if(!$rekord) //Jeśli brak, to nie ma użytkownika o podanym loginie
	{
		$status = 0;
		$location = "Location: index.php?status=$status";
		header($location);
	}
	else
	{ // jeśli $rekord istnieje
		if($rekord['pass']==$pass) // czy hasło zgadza się z BD
		{ 
			//echo "Logowanie Ok. User: {$rekord['login']}. Hasło: {$rekord['pass']}";
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
		
			$query = "INSERT INTO logi (idUser, adresIp, data, godzina, przegladarka, systemOp, poprawnosc) VALUES ('$idUser', '$ip', '$data', '$godzina', '$przegladarka', '$system', '$correct');";
			$base->iuOperation($query);
			
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
			$query = "INSERT INTO logi (idUser, adresIp, data, godzina, przegladarka, systemOp, poprawnosc) VALUES ('$idUser', '$ip', '$data', '$godzina', '$przegladarka', '$system', '$correct');";
			$base->iuOperation($query);
			$status = 1;
			$location = "Location: index.php?status=$status";
			header($location);
		}
	}
	
	
?>
