<!DOCTYPE html>
<html lang="pl">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    
    <style>
        *{
            font-family: 'Open Sans', sans-serif;
        }
        body{
            display: flex;
            align-items: center;
            flex-direction: column;
        }
        table{
            border-collapse: collapse;
            width: 60%;
        }
        table,td,th{
            border: 1px solid;
        }
        td,th{
            padding: 10px;
        }
        tr:nth-child(even){
            background-color: #f2f2f2;
        }
        tr:nth-child(odd){
            background-color: #D1D1D1;
        }
        th{
            background-color: #221B20;
            color: white;
        }
        p{
            color: white;
            font-size: 20px;
        }
    </style>


</head>
<body>
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

    $query = "SELECT users.login AS 'login', logi.adresIp, logi.data, logi.godzina, logi.przegladarka, logi.systemOp FROM logi, users WHERE users.id = logi.idUser";
    $rezultat = mysqli_query($polaczenie, $query) or die ("Logi logowania");
    echo "<p>Logowania uzytkownikow:</p>";
    echo "<table><tr><th>Login</th><th>Adres ip</th><th>Data</th><th>Godzina</th><th>Przegladarka</th><th>System</th></tr>";
    while($rekord = mysqli_fetch_array($rezultat)){
        $login = $rekord['login'];
        $adresIp = $rekord['adresIp'];
        $godzina = $rekord['godzina'];
        $data = $rekord['data'];
        $przegladarka = $rekord['przegladarka'];
        $system  = $rekord['systemOp'];
        echo "<tr><td>$login</td><td>$adresIp</td><td>$godzina</td><td>$data</td><td>$przegladarka</td><td>$system</td></tr>";
    }
    echo "</table>";
    
    mysqli_close($polaczenie);
?>
</body>
</html>