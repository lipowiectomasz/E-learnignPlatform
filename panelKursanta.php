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
		require dirname(__DIR__) . '/PlatformaELearningowa/vendor/autoload.php';
		use panels\panelRenderer;
		session_start();
		if($_SESSION['loggedin']!=true){
			$location = "Location: index.php?status=2";
			header($location);
		}
		$user = $_SESSION['user'];
		$renderer = new panelRenderer();
	?>
	<section id="mainContainer">
		<div id="nav">
			<p> Zalogowano kursant: <?php echo $user; ?> </p>
			<div class="navButton"><a href="wyloguj.php">Wyloguj</a></div>
			<p> Lekcje: </p>
			<?php $renderer->userPanelRenderLessons(); ?>
			<p> Podsumowania: </p>
			<?php $renderer->userPanelRenderSummaries(); ?>
			<p> Testy: </p>
			<?php $renderer->userPanelRenderTests(); ?>
			<p> Wczesniejsze podejscia: </p>
			<?php $renderer->userPanelRenderPriviousTests($user); ?>
		</div>
		<div id="main">
			<iframe name="border" width="100%" height="100%" style="border:none;">
			</iframe>
		</div>
	</body>
</html>