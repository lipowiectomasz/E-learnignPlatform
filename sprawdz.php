<?php
    //use panels\panelRenderer;
    require dirname(__DIR__) . '/PlatformaELearningowa/vendor/autoload.php';
    use databaseOperator\operator;
    session_start();
    $idFormularz = $_GET['id'];
    $amount = $_GET['amount'];
    $idUser = $_SESSION['idUser'];
    $punkty=0;
    //$checker = new panelRenderer();
    $base = new operator();
    if($typ==0){
        $godzina = date('H:i:s', time());
        $data = date ('Y-m-d', time()); 
        $query = "INSERT INTO podejsciaDoTestu (idUser, godzina, data, idFormularz ) VALUES ( $idUser, '$godzina', '$data', $idFormularz)";
        $base->iuOperation($query);  
    }
    $query = "SELECT id FROM podejsciaDoTestu WHERE idUser=$idUser GROUP BY id DESC LIMIT 1";
    $idPodejscie = $base->selectRowOperation($query);
    $idPodejscie = $idPodejscie['id'];
    //Numery pytan
    $x=1;
    while($amount>0){
        $name="Pytanie_".$x;
        $query = "SELECT id FROM elementy WHERE element='$name' AND idFormularza=$idFormularz;";
        $elementId = $base->selectRowOperation($query);
        $elementId = $elementId['id'];
        $query = "SELECT poprawna FROM odpowiedzi WHERE idElement=$elementId;";
        $corr = $base->selectRowOperation($query);
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
        $base->iuOperation($query);      
        $x++;
        $amount--;
    }
    $query = "UPDATE podejsciaDoTestu SET punkty=$punkty WHERE id=$idPodejscie";
    $base->iuOperation($query); 
    header("location: pokazFormularz.php?podejscieId=$idPodejscie&podejscie=0&typ=0&id=$idFormularz");
?>