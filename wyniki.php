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

    $query = "SELECT podejsciaDoTestu.id AS 'podejscieId', formularze.id ,users.login AS 'login', podejsciaDoTestu.godzina, podejsciaDoTestu.data, formularze.tytul, podejsciaDoTestu.punkty FROM podejsciaDoTestu, users, formularze WHERE users.id = podejsciaDoTestu.idUser AND formularze.id = podejsciaDoTestu.idFormularz AND formularze.autor = '$user';";
    $rezultat = mysqli_query($polaczenie, $query) or die ("Problem w wyswietlaniu listy testow dla poszczegolnych podejsc.");
    echo "<table><tr><th>Login</th><th>Godzina</th><th>Data</th><th>Tytul</th><th>Punkty</th><th>Przejscie do formularza</th></tr>";
    while($rekord = mysqli_fetch_array($rezultat)){

        $podejscieId = $rekord['podejscieId'];
        $id = $rekord['id'];
        $login = $rekord['login'];
        $godzina = $rekord['godzina'];
        $data = $rekord['data'];
        $tytul = $rekord['tytul'];
        $punkty = $rekord['punkty'];
        
        echo "<tr><td>$login</td><td>$godzina</td><td>$data</td><td>$tytul</td><td>$punkty</td><td><a href=\"pokazFormularz.php?podejscieId=$podejscieId&podejscie=0&typ=0&id=$id\">Przejdz</a></td></tr>";
    } 
    echo "</table>";
    mysqli_close($polaczenie);
?>
</body>
</html>