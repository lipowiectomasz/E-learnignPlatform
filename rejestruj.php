<?php
	require dirname(__DIR__) . '/PlatformaELearningowa/vendor/autoload.php';
	use databaseOperator\operator;

	$login = htmlentities ($_POST['login'], ENT_QUOTES, "UTF-8"); // rozbrojenie potencjalnej bomby w zmiennej $user
	$pass = htmlentities ($_POST['pass'], ENT_QUOTES, "UTF-8"); // rozbrojenie potencjalnej bomby w zmiennej $pass
	$typ = $_GET['typ'];

	$register = new operator();
	$query = "INSERT INTO users (userType, login, pass) VALUES ( $typ, \"$login\", \"$pass\");";
	$register->iuOperation($query);

	echo 'Pomyslnie dodano usera';
	if($typ==2){
		header("Location: index.php");
	}
?>
