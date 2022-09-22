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
			height: 100px;
			display: flex;
			justify-content: center;
			align-items: center;
			cursor: default;
		}
		.navButton{
			width: 100%;
			height: 100px;
			background-color: #483D45;
			margin: 1% 0 1% 0;
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
		.navButton a:hover{
			background-color: #3B3339;
			color: white;
		}
	</style>


	<title>Tomasz Lipowiec</title>
</head>
<body>
	<header>
		<p>Panel szkoleniowca</p>
	</header>
	<section id="mainContainer">
		<div id="nav">
			<p> Zalogowano - 
			<?php
				session_start();
				if($_SESSION['loggedin']!=true){
					$location = "Location: index.php?status=2";
					header($location);
				}
				else{
					$user = $_SESSION['user'];
					echo $user;
				}
			?>
			</p>
			<div class="navButton"><a href="wyloguj.php">Wyloguj</a></div>
			<div class="navButton"><a href="konfigurujTest.php">Konfiguruj testy</a></div>
			<div class="navButton"><a href="konfigurujLekcje.php">Konfiguruj lekcje</a></div>
			<div class="navButton"><a href="konfigurujPodsumowanie.php">Konfiguruj podsumowanie</a></div>
			<div class="navButton"><a href="wyniki.php" target="border">Pokaz wyniki</a></div>
			<div class="navButton"><a href="pokazLogi.php" target="border">Pokaz logi</a></div>
		</div>
		<div id="main">
			<iframe name="border" width="100%" height="100%" style="border:none;">
			</iframe>
		</div>
	</section>
</body>
</html>
