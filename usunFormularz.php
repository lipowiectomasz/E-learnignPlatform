<?php
    session_start();
    $typ = $_GET['typ'];
    $idFormularz = $_GET['id'];
    $decyzja = $_GET['dec'];

    if($typ==0){
        $location = "konfigurujTest.php";
    }
    elseif($typ==1){
        $location = "konfigurujLekcje.php";
    }
    else{
        $location = "konfigurujPodsumowanie.php";
    }

    if($decyzja==0){
        echo '<p>Czy na pewno chcesz usunac formularz?</p>';
        echo '<button onclick="window.location.href=\'usunFormularz.php?dec=1&typ='.$typ.'&id='.$idFormularz.'\'" type="button">TAK</button>
        <button onclick="window.open(\''.$location.'\', \'_top\')" type="button">NIE</button>
        ';
    }
    else{
        require 'dbInfo.php';
        $polaczenie = mysqli_connect($dbhost, $dbuser, $dbpassword, $dbname);
        mysqli_query($polaczenie, "SET NAMES 'utf8'");

        if($typ==0){

            //usun informacje z tabeli odpowiedziUsera
            $query = "DELETE odpowiedziUsera FROM odpowiedziUsera INNER JOIN podejsciaDoTestu ON odpowiedziUsera.idPodejscia = podejsciaDoTestu.id WHERE podejsciaDoTestu.idFormularz=$idFormularz";
            $rezultat = mysqli_query($polaczenie, $query) or die ("Problem przy usuwaniu informacji z tabeli odpowiedziUsera");

            //usun informacje z tabeli podejscia do testu
            $query = "DELETE FROM podejsciaDoTestu WHERE idFormularz=$idFormularz";
            $rezultat = mysqli_query($polaczenie, $query) or die ("Problem przy usuwaniu informacji z tabeli podejscia do testu");

            //usun informacje z tabeli odpowiedzi
            $query = "DELETE odpowiedzi FROM odpowiedzi INNER JOIN elementy ON odpowiedzi.idElement = elementy.id WHERE elementy.idFormularza=$idFormularz";
            $rezultat = mysqli_query($polaczenie, $query) or die ("Problem przy usuwaniu informacji z tabeli odpowiedzi");

            //usun informacje z tabeli elementy
            $query = "DELETE FROM elementy WHERE idFormularza=$idFormularz";
            $rezultat = mysqli_query($polaczenie, $query) or die ("Problem przy usuwaniu informacji z tabeli elementy");

            //usun informacje z tabeli formularze
            $query = "DELETE FROM `formularze` WHERE id=$idFormularz";
            $rezultat = mysqli_query($polaczenie, $query) or die ("Problem przy usuwaniu informacji z tabeli formularze");
            mysqli_close($polaczenie);

        }
        if($typ==1 || $typ==2){
            //usun informacje z tabeli odpowiedzi
            $query = "DELETE odpowiedzi FROM odpowiedzi INNER JOIN elementy ON odpowiedzi.idElement = elementy.id WHERE elementy.idFormularza=$idFormularz";
            $rezultat = mysqli_query($polaczenie, $query) or die ("Problem przy usuwaniu informacji z tabeli odpowiedzi");

            //usun informacje z tabeli elementy
            $query = "DELETE FROM elementy WHERE idFormularza=$idFormularz";
            $rezultat = mysqli_query($polaczenie, $query) or die ("Problem przy usuwaniu informacji z tabeli elementy");

            //usun informacje z tabeli formularze
            $query = "DELETE FROM `formularze` WHERE id=$idFormularz";
            $rezultat = mysqli_query($polaczenie, $query) or die ("Problem przy usuwaniu informacji z tabeli formularze");

            mysqli_close($polaczenie);
        }
        echo '<script> 
                window.onload=function(){
                    window.open(\''.$location.'\', \'_top\')
                }
                 </script>';
    }
    
?>