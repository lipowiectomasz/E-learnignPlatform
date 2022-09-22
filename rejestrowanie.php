<?php
	session_start();
	if($_SESSION['loggedin']!=true){
		$location = "Location: index.php?status=2";
		header($location);
	}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<!--<link rel="stylesheet" href="style.css" type="text/css"/>-->
	<!--<script src="script.js"></script>-->
	<style>
		*{
			margin: 0;
		}
		body{
			width: 100%;
			height: 100vh;
			display: flex;
			align-items: center;
			justify-content: center; 
		}
		#logginBox{
			font-family: 'Open Sans', sans-serif;
			font-size: 24px;
			width: 600px;
			height: 400px;
			background-color: #3B3339; 
			display: flex;
			align-items: center;
			justify-content: center; 
			flex-direction: column;
		}
		#formContainer{
			display: flex;
			flex-wrap: wrap;
			flex-direction: row;
			justify-content: center;
			align-items: center;
			width: 320px;
		}
		#formContainer input{
			margin: 7px 0 7px 0;
			height: 30px;
			width: 200px;
		}
		#formContainer label{
			display: inline-block;
			width: 100px;
		}
		#logginBox p{
			font-size: 26px;
			margin-bottom: 20px;
		}

	</style>
	<title>Tomasz Lipowiec</title>
</head>
<body>
	<section id="logginBox">
		<p>Rejestracja</p>
		<form method="post" action="rejestruj.php?typ=1" id="formContainer">
			<label>Login: </label><input type="text" name="login" maxlength="30" size="30"><br>
			<label>Hasło: </label><input type="password" name="pass" maxlength="30" size="30"><br>
			<input type="submit" value="Zarejestruj"/>
		</form>
	</section>
</body>
</html>
