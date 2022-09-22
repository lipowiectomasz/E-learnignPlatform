<?php
    session_start();
    $idFormularz = $_GET['id'];
    $typ = $_GET['typ'];
    $podejscieId = $_GET['podejscieId'];
    ob_start();
    require_once("fpdf/fpdf.php");

    $pdf = new FPDF('P','mm','A4');
    $pdf -> AddPage();
    
    
    require 'dbInfo.php';
    $polaczenie = mysqli_connect($dbhost, $dbuser, $dbpassword, $dbname);
    mysqli_query($polaczenie, "SET NAMES 'utf8'");

    $query = "SELECT id, element, tresc, grafika FROM elementy WHERE idFormularza=$idFormularz;";
    $rezultat = mysqli_query($polaczenie, $query) or die ("Blad w pobraniu danych formularza $idFormularz");

    $amt=0;
    
    

    while($rekord = mysqli_fetch_array($rezultat)){

        $id = $rekord['id'];
        $element = $rekord['element'];
        $tresc = $rekord['tresc'];
        $grafika = $rekord['grafika'];

        if($typ==0 && $podejscie==0){

            if($amt == 0){
                $pdf ->SetFont('Arial', 'B', 15);
                $pdf ->Cell(20,20,"$element",0,1);
                $pdf ->SetFont('Arial', '', 11);
                $pdf ->Cell(10,10,"$tresc",0,1);
                $tytul = $tresc;
                if($grafika!='-'){
                    $pdf -> Image("Images/$grafika");
                }
            }
            if($amt >= 1){
                
                $queryPokazPodejscie = "SELECT odpowiedz FROM odpowiedziUsera WHERE elementId=$id AND idPodejscia=$podejscieId;";
                $rezultatPokazPodejscie = mysqli_query($polaczenie, $queryPokazPodejscie);
                if ($rezultatPokazPodejscie->num_rows > 0){
                    $rekordPokazPodejscie = mysqli_fetch_array($rezultatPokazPodejscie);


                    $pdf ->SetFont('Arial', 'B', 15);
                    $pdf ->Cell(10,10,"$element",0,1);
                    $pdf ->SetFont('Arial', '', 11);
                    $pdf ->Cell(10,10,"$tresc",0,1);
                    if($grafika!='-'){
                        $pdf -> Image("Images/$grafika");
                    }

                    $odpowiedz = $rekordPokazPodejscie['odpowiedz'];

                    $queryOdp = "SELECT A, B, C, D, poprawna FROM odpowiedzi WHERE idElement=$id;";
                    $rezultatOdp = mysqli_query($polaczenie, $queryOdp);
                    $rekordOdp = mysqli_fetch_array($rezultatOdp);
                    
                    $pdf ->SetFont('Arial', 'B', 11);
                    $pdf ->Cell(10,10,"Odp A:",0,1);
                    $pdf ->SetFont('Arial', '', 11);
                    if($rekordOdp['poprawna']=='A'){
                        $pdf ->SetTextColor(51,204,51);
                        $pdf ->Cell(10,10,"".$rekordOdp['A']."",0,1);
                    }
                    else {
                        if($odpowiedz=='A'){
                            $pdf ->SetTextColor(255,51,0);
                            $pdf ->Cell(10,10,"".$rekordOdp['A']."",0,1);
                        }
                        else{
                            $pdf ->SetTextColor(0,0,0);
                            $pdf ->Cell(10,10,"".$rekordOdp['A']."",0,1);
                        }
                    }
                    $pdf ->SetTextColor(0,0,0);
                    $pdf ->SetFont('Arial', 'B', 11);
                    $pdf ->Cell(10,10,"Odp B:",0,1);
                    $pdf ->SetFont('Arial', '', 11);
                    if($rekordOdp['poprawna']=='B'){
                        $pdf ->SetTextColor(51,204,51);
                        $pdf ->Cell(10,10,"".$rekordOdp['B']."",0,1);
                    }
                    else {
                        if($odpowiedz=='B'){
                            $pdf ->SetTextColor(255,51,0);
                            $pdf ->Cell(10,10,"".$rekordOdp['B']."",0,1);
                        }
                        else{
                            $pdf ->SetTextColor(0,0,0);
                            $pdf ->Cell(10,10,"".$rekordOdp['B']."",0,1);
                        }
                    }

                    $pdf ->SetTextColor(0,0,0);
                    $pdf ->SetFont('Arial', 'B', 11);
                    $pdf ->Cell(10,10,"Odp C:",0,1);
                    $pdf ->SetFont('Arial', '', 11);
                    if($rekordOdp['poprawna']=='C'){
                        $pdf ->SetTextColor(51,204,51);
                        $pdf ->Cell(10,10,"".$rekordOdp['C']."",0,1); 
                    }
                    else {
                        if($odpowiedz=='C'){
                            $pdf ->SetTextColor(255,51,0);
                            $pdf ->Cell(10,10,"".$rekordOdp['C']."",0,1);
                        }
                        else{
                            $pdf ->SetTextColor(0,0,0);
                            $pdf ->Cell(10,10,"".$rekordOdp['C']."",0,1);
                        }
                    }

                    $pdf ->SetTextColor(0,0,0);
                    $pdf ->SetFont('Arial', 'B', 11);
                    $pdf ->Cell(10,10,"Odp D:",0,1);
                    $pdf ->SetFont('Arial', '', 11);
                    if($rekordOdp['poprawna']=='D'){
                        $pdf ->SetTextColor(51,204,51);
                        $pdf ->Cell(10,10,"".$rekordOdp['D']."",0,1);
                    }
                    else {
                        if($odpowiedz=='D'){
                            $pdf ->SetTextColor(255,51,0);
                            $pdf ->Cell(10,10,"".$rekordOdp['D']."",0,1);
                        }
                        else{
                            $pdf ->SetTextColor(0,0,0);
                            $pdf ->Cell(10,10,"".$rekordOdp['D']."",0,1);
                        }
                    }

                }
            }
            
            $amt++;
        }
    }
    
    $query = "SELECT podejsciaDoTestu.punkty, users.login FROM podejsciaDoTestu, users WHERE podejsciaDoTestu.id=$podejscieId AND podejsciaDoTestu.idUser = users.id";
    $rezultat = mysqli_query($polaczenie, $query) or die("Blad w pobieraniu punktow i loginu");
    $rekord = mysqli_fetch_array($rezultat);
    

    $punkty = $rekord['punkty'];
    $login = $rekord['login'];
    $pdf ->SetFont('Arial', 'B', 11);
    --$amt;
    $pdf ->Cell(10,10,"Podsumowanie. Punktacja: $punkty / $amt",0,1);
    mysqli_close($polaczenie);
    $pdf ->Output("$login $tytul","I");
    ob_end_flush();
?>