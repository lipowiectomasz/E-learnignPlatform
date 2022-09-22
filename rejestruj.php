<?php
	$login = $_POST['login'];
	$pass = $_POST['pass'];
	$typ = $_GET['typ'];

	require 'dbInfo.php';
	$polaczenie = mysqli_connect($dbhost, $dbuser, $dbpassword, $dbname);
	mysqli_query($polaczenie, "SET NAMES 'utf8'");

	$query = "INSERT INTO users (userType, login, pass) VALUES ( $typ, \"$login\", \"$pass\");";
	$rezultat = mysqli_query($polaczenie, $query) or die ("Blad w dodawaniu usera");

	mysqli_close($polaczenie);
	echo 'Pomyslnie dodano usera';
	if($typ==2){
		header("Location: index.php");
	}
?>
