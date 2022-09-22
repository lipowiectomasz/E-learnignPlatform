<!DOCTYPE html>
<html lang="pl">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<!--<link rel="stylesheet" href="style.css" type="text/css"/>-->
	
	<meta desctiption="" />
	<meta keywords=""/>
	
	<!--<script src="script.js"></script>-->
	<title>Tomasz Lipowiec</title>
</head>
<body>
	Formularz logowania
	<form method="post" action="zaloguj.php" target="_top">
	  	<input type="radio" id="pracownik" name="type" value="pracownik">
	  	<label for="pracownik">pracownik</label><br>
	  	<input type="radio" id="klient" name="type" value="klient">
	  	<label for="klient">klient</label><br>
		Login:<input type="text" name="user" maxlength="20" size="20"><br>
		Hasło:<input type="password" name="pass" maxlength="20" size="20"><br>
		<input type="submit" value="Send"/>
	</form>
</body>
</html>
