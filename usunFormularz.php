<?php
    require dirname(__DIR__) . '/PlatformaELearningowa/vendor/autoload.php';
    use databaseOperator\operator;

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
        $base = new operator();
        if($typ==0){

            //usun informacje z tabeli odpowiedziUsera
            $query = "DELETE odpowiedziUsera FROM odpowiedziUsera INNER JOIN podejsciaDoTestu ON odpowiedziUsera.idPodejscia = podejsciaDoTestu.id WHERE podejsciaDoTestu.idFormularz=$idFormularz";
            $base->iuOperation($query);

            //usun informacje z tabeli podejscia do testu
            $query = "DELETE FROM podejsciaDoTestu WHERE idFormularz=$idFormularz";
            $base->iuOperation($query);

            //usun informacje z tabeli odpowiedzi
            $query = "DELETE odpowiedzi FROM odpowiedzi INNER JOIN elementy ON odpowiedzi.idElement = elementy.id WHERE elementy.idFormularza=$idFormularz";
            $base->iuOperation($query);
            
            //usun informacje z tabeli elementy
            $query = "DELETE FROM elementy WHERE idFormularza=$idFormularz";
            $base->iuOperation($query);
            
            //usun informacje z tabeli formularze
            $query = "DELETE FROM `formularze` WHERE id=$idFormularz";
            $base->iuOperation($query);

        }
        if($typ==1 || $typ==2){
            //usun informacje z tabeli odpowiedzi
            $query = "DELETE odpowiedzi FROM odpowiedzi INNER JOIN elementy ON odpowiedzi.idElement = elementy.id WHERE elementy.idFormularza=$idFormularz";
            $base->iuOperation($query);
            
            //usun informacje z tabeli elementy
            $query = "DELETE FROM elementy WHERE idFormularza=$idFormularz";
            $base->iuOperation($query);
            
            //usun informacje z tabeli formularze
            $query = "DELETE FROM `formularze` WHERE id=$idFormularz";
            $base->iuOperation($query);
        }
        echo '<script> 
                window.onload=function(){
                    window.open(\''.$location.'\', \'_top\')
                }
                 </script>';
    }
    
?>