<?php
    session_start();
    $idFormularz = $_GET['id'];
    $amount = $_GET['amount'];
    $idUser = $_SESSION['idUser'];
    $punkty=0;
    $godzina = date('H:i:s', time());
	$data = date ('Y-m-d', time()); 
    require 'dbInfo.php';
	$polaczenie = mysqli_connect($dbhost, $dbuser, $dbpassword, $dbname);
    mysqli_query($polaczenie, "SET NAMES 'utf8'");

    if($typ==0){
        $query = "INSERT INTO podejsciaDoTestu (idUser, godzina, data, idFormularz ) VALUES ( $idUser, '$godzina', '$data', $idFormularz)";
        $rezultat = mysqli_query($polaczenie, $query) or die ("Blad we wpisywaniu logow do podejscia do testu");
    }

    $query = "SELECT id FROM podejsciaDoTestu WHERE idUser=$idUser GROUP BY id DESC LIMIT 1";
    $rezultat = mysqli_query($polaczenie, $query) or die ("Blad w pobieraniu id podejscia");
    $idPodejscie = mysqli_fetch_array($rezultat);
    $idPodejscie = $idPodejscie['id'];
    //Numery pytan
    $x=1;
    
    while($amount>0){
        $name="Pytanie_".$x;
        
        $query = "SELECT id FROM elementy WHERE element='$name' AND idFormularza=$idFormularz;";
        $rezultat = mysqli_query($polaczenie, $query) or die ("Blad w pobraniu id elementu $x");
        $elementId = mysqli_fetch_array($rezultat);
        $elementId = $elementId['id'];

        $query = "SELECT poprawna FROM odpowiedzi WHERE idElement=$elementId;";
        $rezultat = mysqli_query($polaczenie, $query) or die ("DB error w pobieraniu poprawnej odpowiedzi pytania $x");
        $rekord = mysqli_fetch_array($rezultat);
        $corr = $rekord['poprawna'];

        if(isset($_POST["zaznaczona".$x]) ){
            $zaznaczona=$_POST["zaznaczona".$x];
            if($zaznaczona==$corr){
                $punkty++;
            }
        }
        else{
            $zaznaczona='-';
        }

        $query = "INSERT INTO odpowiedziUsera (idPodejscia, elementId, odpowiedz) VALUES ($idPodejscie, $elementId, '$zaznaczona')";
        $rezultat = mysqli_query($polaczenie, $query) or die ("DB error w zapisywaniu odpowiedzi usera $x");       

        $x++;
        $amount--;
    }
    $query = "UPDATE podejsciaDoTestu SET punkty=$punkty WHERE id=$idPodejscie";
    $rezultat = mysqli_query($polaczenie, $query) or die ("DB error w zapisywaniu punktow usera $x");
    
    header("location: pokazFormularz.php?podejscieId=$idPodejscie&podejscie=0&typ=0&id=$idFormularz");
    mysqli_close($polaczenie);
?>