<!DOCTYPE html>
<html lang="pl">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<!-- <link rel="stylesheet" href="style.css" type="text/css"/> -->
	<style>
		*{
			margin: 0;
			font-family: 'Open Sans', sans-serif;
		}
		body{
			width: 100%;
			height: 100vh;
			display: flex;
			align-items: center;
			justify-content: center; 
		}
		#logginBox{
			
			font-size: 24px;
			width: 600px;
			height: 400px;
			background-color: #3B3339; 
			display: flex;
			align-items: center;
			justify-content: center; 
			flex-direction: column;
		}
		#signIn{
			text-decoration: none;
			font-size: 18px;
			margin-top: 30px;
		}
		#signIn:hover{
			color: red;
			cursor: default;
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
		#description{
			position: absolute;
			width: 700px;
			height: 400px;
			background-color: #e6e6e6;
			padding: 20px;
			font-size: 20px;
			display: flex;
			justify-content: center;
			align-items: center;
			flex-direction: column;
			border-radius: 5px;
		}
		#description p{
			margin: 10px 0 10px 0;
		}
		#description p:first-child{
			font-weight: bold;
		}
		#showDescription{
			width: 80px;
			height: 80px;
			display: flex;
			justify-content: center;
			align-items: center;
			background-color: #e6e6e6;
			position: absolute;
			top: 120px;
			left: 500px;
			cursor: default;
			font-weight: bold;
			border-radius: 10px;
			animation-name: pulse;
			animation-duration: 3s;
			animation-iteration-count: infinite;
		}
		#showDescription:hover{
			background-color: #a6a6a6;
		}
		@keyframes pulse {
			0% {transform: scale(1);}
			50% {transform: scale(1.2);}
			100% {transform: scale(1);}
		}
	</style>
	<!--<script src="script.js"></script>-->
	<title>Tomasz Lipowiec</title>
</head>
<body>
	<div id="showDescription">Info</div>
	<div id="description" style="display:none">
		<p>Projekt aplikacji realizuj??cy platform?? e-learningow??</p>
		<p>Mog?? z niej korzysta?? u??ytkownicy o trzech stopniach uprzywilejowania: administrator, szkoleniowiec oraz kursant</p>
		<p>Maj?? oni do dyspozycji nast??puj??ce funkcje: </p>
		<ul>
			<li>Administrator - rejestracja szkoleniowc??w, przegl??danie log??w (Przyk??adowy administrator: admin-admin).</li>
			<li>Szkoleniowiec - konfigurowanie test??w, lekcji oraz podsumowa??, przegl??danie wynik??w kursant??w i log??w (Przyk??adowy szkoleniowiec coach1-pass1).</li>
			<li>Kursant - przegl??danie lekcji i podsumowa??, branie udzia??u w testach i generowanie wynik??w w PDF (Przyk??adowy kursant user1-pass1).</li>
		</ul>
	</div>

	<?php	
		require dirname(__DIR__) . '/PlatformaELearningowa/vendor/autoload.php';
		session_start();
		if(IsSet($_SESSION ['try'])){
			if($_SESSION ['try'] >= 3){
				if($_SESSION['try']==3){
					$_SESSION['waitTimeStop']=strtotime(date('H:i:s'))+60;
					//echo "START: ".$_SESSION['waitTimeStart'];
				}
				echo "<script> alert('Przekroczono limit prob logowania, sproboj ponownie za minute') </script>";
				
				// sleep(60);
			}		
		}

		if(IsSet($_GET['status'])){
			$status = $_GET['status'];
			if($status == "0" || $status == "1"){
				echo "<script> alert('B????dne dane. Spr??buj ponownie.') </script>";
			}
			if($status == "2"){
				echo "<script> alert('Zaloguj si?? aby przej???? do podstrony.') </script>";
			}
		}	
	?>
	<section id="logginBox">
		<p>Logowanie</p>
		<form method="post" action="zaloguj.php" id="formContainer">
			<label>Login: </label><input type="text" name="user" maxlength="30" size="30"><br>
			<label>Has??o: </label><input type="password" name="pass" maxlength="30" size="30"><br>
			<input type="submit" value="Zaloguj"/>
		</form>	
		<a id="signIn">Zarejestruj si??</a>
	</section>


	<script>	
		const logginBox = document.getElementById("logginBox");
		const signInLink = document.getElementById("signIn");
		var logging = true;
		const logInForm = logginBox.innerHTML;
		signInForm = "<p>Rejestracja</p><form method=\"post\" action=\"rejestruj.php?typ=2\" id=\"formContainer\"><label>Login: </label><input type=\"text\" name=\"login\" maxlength=\"30\" size=\"30\"><br\><label>Has??o: </label><input type=\"password\" name=\"pass\" maxlength=\"30\" size=\"30\"><br\><input type=\"submit\" value=\"Zarejestruj\"/></form><a id=\"signIn\">Zaloguj si??</a>";

		function toggleForm(){
			if(logging){
				logginBox.innerHTML = signInForm;
				logging = false;
				document.getElementById("signIn").addEventListener("click", toggleForm);
			}
			else{
				logginBox.innerHTML = logInForm;
				logging = true;
				document.getElementById("signIn").addEventListener("click", toggleForm);
			}
		}
		signInLink.addEventListener("click", toggleForm);

		var infoButton = document.getElementById("showDescription");
		var description = document.getElementById("description");
		//description.style.display = "none !important";

		infoButton.addEventListener("click", () => {
			console.log("STYLE"+description.style.display);
			if(description.style.display == "none"){
				description.style.display = "initial";
			}
			else{
				description.style.display = "none";
			}
		});


	</script>

</body>
</html>
