<!DOCTYPE html>
<html lang="pl">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <style>
		*{
			margin: 0;
			font-family: 'Open Sans', sans-serif;
		}
		body{
			height: 100%; 
			width: 100%;
		}
        header{
			width: 100%;
			height: 10vh;
			background-color: #3B3339;
			display: flex;
			justify-content: center;
			align-items: center;
		}
		header p{
			color:#FFA9E9;
			font-size: 35px;
			/* text-align: center; */
		}
		#mainContainer{
			width: 100%;
			height: 90vh;
			display: flex;
		}
		#nav{
			/* height: 500px;
			width: 300px; */
			height: 100%;
			width: 20%;
			background-color: #554751;
			display: flex;
			flex-direction: column;
			font-size: 25px;
		}
		#main{
			/* height: 500px;
			width: 1200px; */
			height: 90vh;
			width: 80%;
			background-color: #6E5A69;
		}
        #nav p:first-child{
            font-weight: bold;
        }
		#nav p{
			height: 100px;
			display: flex;
			justify-content: center;
			align-items: center;
			cursor: default;
		}
		.navButton, .navButtonSplit{
			width: 100%;
			height: 100px;
            background-color: #483D45;
			margin: 1% 0 1% 0;
		}
        .navButtonSplit{
            /* margin: 3px 0 3px 0; */
            border: 1px;
            border-top-style: solid;
            border-bottom-style: solid;
        }
        .navButtonSplit a{
            display: flex;
			justify-content: center;
			align-items: center;
			width: 100%;
			height: 50%;
			text-decoration: none;
			color: black;
        }
		.navButton a{
			display: flex;
			justify-content: center;
			align-items: center;
			width: 100%;
			height: 100%;
			text-decoration: none;
			color: black;
		}
		.navButton a:hover, .navButtonSplit a:hover{
			background-color: #3B3339;
			color: white;
		}
	</style>
    <title>Tomasz Lipowiec</title>
</head>
<body>
    <header>
		<p>Panel użytkownika</p>
	</header>
<?php
	session_start();
	
	if($_SESSION['loggedin']!=true){
		$location = "Location: index.php?status=2";
		header($location);
	}

	$user = $_SESSION['user'];
	
	require 'dbInfo.php';
	$polaczenie = mysqli_connect($dbhost, $dbuser, $dbpassword, $dbname);
	mysqli_query($polaczenie, "SET NAMES 'utf8'");
	echo '<section id="mainContainer">
			<div id="nav">
				<p> Zalogowano kursant: '.$user.' </p>
				<div class="navButton"><a href="wyloguj.php">Wyloguj</a></div>
				<p> Lekcje: </p>
		';
		$query = "SELECT id, tytul FROM formularze WHERE typ=1;";
		$rezultat = mysqli_query($polaczenie, $query) or die ("Problem w wyswietlaniu listy lekcji.");
		$tests = array();
		$i = 1;
		while($rekord = mysqli_fetch_array($rezultat)){
			$id = $rekord['id'];
			$title = $rekord['tytul'];

			$tests[$i][0] = $title;
			$tests[$i][1] = $id;
			$i++;
		} 
		if($i > 1){
			foreach( $tests as $t)
			{
				echo '<div class="navButton"><a href="pokazFormularz.php?typ=1&id='.$t[1].'" target="border">'.$t[0].'</a></div>';
			}
		}
	echo '<p> Podsumowania: </p>';
		$query = "SELECT id, tytul FROM formularze WHERE typ=2;";
		$rezultat = mysqli_query($polaczenie, $query) or die ("Problem w wyswietlaniu listy podsumowan.");
		$tests = array();
		$i = 1;
		while($rekord = mysqli_fetch_array($rezultat)){
			$id = $rekord['id'];
			$title = $rekord['tytul'];

			$tests[$i][0] = $title;
			$tests[$i][1] = $id;
			$i++;
		} 
		if($i > 1){
			foreach( $tests as $t)
			{
				echo '<div class="navButton"><a href="pokazFormularz.php?typ=2&id='.$t[1].'" target="border">'.$t[0].'</a></div>';
			}
		}
	echo '<p> Testy: </p>';
		$query = "SELECT id, tytul, czas FROM formularze WHERE typ=0;";
		$rezultat = mysqli_query($polaczenie, $query) or die ("Problem w wyswietlaniu listy testow.");
		$tests = array();
		$i = 1;
		while($rekord = mysqli_fetch_array($rezultat)){
			$id = $rekord['id'];
			$title = $rekord['tytul'];
			$czas = $rekord['czas'];

			$tests[$i][0] = $title;
			$tests[$i][1] = $id;
			$tests[$i][2] = $czas;
			
			$i++;
		} 
		if($i > 1){
			foreach( $tests as $t)
			{
				echo '<div class="navButton"><a href="pokazFormularz.php?podejscie=1&typ=0&id='.$t[1].'&time='.$t[2].'" target="border">'.$t[0].'</a></div>';
			}
		}
	echo '<p> Wczesniejsze podejscia: </p>';
	$query = "SELECT podejsciaDoTestu.id AS 'podejscieId', formularze.id ,users.login AS 'login', podejsciaDoTestu.godzina, podejsciaDoTestu.data, formularze.tytul, podejsciaDoTestu.punkty FROM podejsciaDoTestu, users, formularze WHERE users.id = podejsciaDoTestu.idUser AND formularze.id = podejsciaDoTestu.idFormularz AND login='$user';";
		$rezultat = mysqli_query($polaczenie, $query) or die ("Problem w wyswietlaniu listy testow.");
		$tests = array();
		$i = 1;
		while($rekord = mysqli_fetch_array($rezultat)){
			$id = $rekord['id'];
			$title = $rekord['tytul'];
			$podejscieId = $rekord['podejscieId'];

			$tests[$i][0] = $title;
			$tests[$i][1] = $id;
			$tests[$i][2] = $podejscieId;
			$i++;
		} 
		if($i > 1){
			foreach( $tests as $t)
			{
				echo '<div class="navButton"><a href="pokazFormularz.php?podejscieId='.$t[2].'&podejscie=0&typ=0&id='.$t[1].'" target="border">'.$t[0].'</a></div>';
			}
		}
	echo '

			</div>
			<div id="main">
				<iframe name="border" width="100%" height="100%" style="border:none;">
					
				</iframe>
			</div>
				
				
			</body>
			</html>
		';

	mysqli_close($polaczenie);
?>