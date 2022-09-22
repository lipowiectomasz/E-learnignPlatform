<?php
	session_start();
	if($_SESSION['loggedin']==true){
		echo '	
			';
	}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<!-- <link rel="stylesheet" href="style.css" type="text/css"/> -->
	<!-- <script src="ckeditor5-build-classic/ckeditor.js"></script> -->
	<title>Tomasz Lipowiec</title>
	<?php
		session_start();
		if($_SESSION['loggedin']!=true){
			$location = "Location: index.php?status=2";
			header($location);
		}
	?>
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
			height: 100%;
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
</head>
<body>
	<header>
		<p>Panel administratora</p>
	</header>
	<section id="mainContainer">
		<div id="nav">
			<p> Zalogowano - admin </p>
			<div class="navButton"><a href="wyloguj.php">Wyloguj</a></div>
			<div class="navButton"><a href="rejestrowanie.php?typ=1" target="border">Zarejestruj szkoleniowca</a></div>
			<div class="navButton"><a href="pokazLogiAdmin.php" target="border">Pokaz logi</a></div>
		</div>
		
		<div id="main">
			<iframe name="border" width="100%" height="750px" style="border:none;">
			</iframe>
		</div>
	</section>
</body>
</html>