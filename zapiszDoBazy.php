<?php
    require dirname(__DIR__) . '/PlatformaELearningowa/vendor/autoload.php';
    use databaseOperator\operator;

    session_start();
    if(!isset($_SESSION['loggedin'])&&$_SESSION['loggedin']!=true){
        $location = "Location: index.php?status=2";
        header($location);
    }
    $user = $_SESSION['user'];
    $idFormularza = $_GET['id'];
    $amount = $_GET['amount'];
    $typ = $_GET['typ'];

    $base = new operator();
    
    if($typ==0){
        $czas = $_GET['time'];
        if(isset($_GET['new'])&&$_GET['new']==1){
            //Wstawianie testu
            $tresc = $_POST['Tytul'];
            $query = "INSERT INTO formularze (tytul, typ, autor, czas) VALUES ('$tresc', '$typ', '$user', $czas);";
            $base->iuOperation($query);
            //Wstawienie tematu
            $query = "INSERT INTO elementy ( idFormularza, element, tresc) VALUES ( $idFormularza, 'Tytul', '$tresc');";
            $base->iuOperation($query);
            //Wstawianie pierwszego pytania
            $tresc = $_POST['Pytanie_1'];
            $file = $base->checkNewFile($_FILES, 1);
            $query = "INSERT INTO elementy ( idFormularza, element, tresc, grafika) VALUES ( $idFormularza, 'Pytanie_1', '$tresc', '$file');";
            $base->iuOperation($query);
            $query = "SELECT id FROM elementy WHERE idFormularza = $idFormularza AND element = 'Pytanie_1';";
            $idElement = $base->selectRowOperation($query);
            $idElement = $idElement['id'];
            $A=$_POST["A1"];
            $B=$_POST["B1"];
            $C=$_POST["C1"];
            $D=$_POST["D1"];
            $corr=$_POST["correct1"];
            $query = "INSERT INTO odpowiedzi (idElement, A, B, C, D, poprawna) VALUES ($idElement, '$A', '$B', '$C', '$D', '$corr');";
            $base->iuOperation($query);
        }
        else {
            echo 'Aktualizacja';
            //Aktualizacja tytulu testu
            $tresc = $_POST['Tytul'];
            $query = "UPDATE formularze SET tytul = '$tresc' WHERE id = $idFormularza;";
            $base->iuOperation($query);
            //Aktualizacja tematu
            $tresc = $_POST['Tytul'];
            $query = "UPDATE elementy SET tresc = '$tresc' WHERE idFormularza = $idFormularza AND element = 'Tytul';";
            $base->iuOperation($query);
            //Aktualizacja pierwszego pytania
            $tresc = $_POST['Pytanie_1'];
            $query = $base->checkFile($_FILES, 1, $tresc, $idFormularza, 'Pytanie_1');
            $base->iuOperation($query);
            $query = "SELECT id FROM elementy WHERE idFormularza = $idFormularza AND element = 'Pytanie_1';";
            $rekord = $base->selectRowOperation($query);
            $idElement = $rekord['id'];
            $A=$_POST["A1"];
            $B=$_POST["B1"];
            $C=$_POST["C1"];
            $D=$_POST["D1"];
            $corr=$_POST["correct1"];
            $query = "UPDATE odpowiedzi SET A='$A', B='$B', C='$C', D='$D', poprawna='$corr' WHERE idElement = $idElement;";
            $base->iuOperation($query);
        }
        //Liczba pytan do dodania badz do zaktualizowania
        $amount = $amount - 1;
        //Numery pytan
        $x=2;
        while($amount>0){
            //Jezeli nazwa elementu to np. "Pytanie 1" oznacza to, ze jest ono w bazie i wystarczy zaktualizowac tresc,
            //jezeli nazwa no np. "1" oznacza, ze trzeba je dodac do bazy.
            $name="Pytanie_".$x;
            if(!isset($_POST[$name])){
                $tresc = $_POST[$x];
                $file = $base->checkNewFile($_FILES, $x);
                $query = "INSERT INTO elementy ( idFormularza, element, tresc, grafika) VALUES ( $idFormularza, '$name', '$tresc', '$file');";
                $base->iuOperation($query);
                $query = "SELECT id FROM elementy ORDER BY id DESC LIMIT 1;";
                $rekord = $base->selectRowOperation($query);
                $idElement = $rekord['id'];
                $A=$_POST["A".$x];
                $B=$_POST["B".$x];
                $C=$_POST["C".$x];
                $D=$_POST["D".$x];
                $corr=$_POST["correct".$x];
                $query = "INSERT INTO odpowiedzi (idElement, A, B, C, D, poprawna) VALUES ($idElement, '$A', '$B', '$C', '$D', '$corr');";
                $base->iuOperation($query);
            }
            else {
                $tresc = $_POST[$name];
                $query = $base->checkFile($_FILES, $x, $tresc, $idFormularza, $name);
                $base->iuOperation($query);
                $query = "SELECT id FROM elementy WHERE idFormularza = $idFormularza AND element = '$name';";
                $rekord = $base->selectRowOperation($query);
                $idElement = $rekord['id'];
                $A=$_POST["A".$x];
                $B=$_POST["B".$x];
                $C=$_POST["C".$x];
                $D=$_POST["D".$x];
                $corr=$_POST["correct".$x];
                $query = "UPDATE odpowiedzi SET A='$A', B='$B', C='$C', D='$D', poprawna='$corr' WHERE idElement = $idElement;";
                $base->iuOperation($query);
            }
            $x++;
            $amount--;
        }
    }

    if($typ==1 || $typ==2){  
        if(isset($_GET['new'])&&$_GET['new']==1){
            echo 'Nowa lekcja/podsumowanie';
            //Wstawianie lekcji
            $tresc = $_POST['Tytul'];
            $query = "INSERT INTO formularze (tytul, typ, autor) VALUES ('$tresc', '$typ', '$user');";
            $base->iuOperation($query);
            //Wstawienie tematu
            $tresc = $_POST['Tytul'];
            $query = "INSERT INTO elementy ( idFormularza, element, tresc) VALUES ( $idFormularza, 'Tytul', '$tresc');";
            $base->iuOperation($query);
            //Wstawianie pierwszego pytania
            $tresc = $_POST['Podpunkt_1'];
            $file = $base->checkNewFile($_FILES, 1);
            $query = "INSERT INTO elementy ( idFormularza, element, tresc, grafika) VALUES ( $idFormularza, 'Podpunkt_1', '$tresc', '$file');";
            $base->iuOperation($query);
        }
        else {
            //Aktualizacja
            //Aktualizacja tematu w formularzach
            $tresc = $_POST['Tytul'];
            $query = "UPDATE formularze SET tytul = '$tresc' WHERE id = $idFormularza;";
            $base->iuOperation($query);
            //Aktualizacja tematu
            $tresc = $_POST['Tytul'];
            $query = "UPDATE elementy SET tresc = '$tresc' WHERE idFormularza = $idFormularza AND element = 'Tytul';";
            $base->iuOperation($query);
            //Aktualizacja pierwszego pytania
            $tresc = $_POST['Podpunkt_1'];
            $query = $base->checkFile($_FILES, 1, $tresc, $idFormularza, 'Podpunkt_1');
            $base->iuOperation($query);
        }
        //Liczba podpunktow do dodania badz do zaktualizowania
        $amount = $amount - 1;
        //Numery podpunktow
        $x=2;
        while($amount>0){
            //Jezeli nazwa elementu to np. "Pytanie 1" oznacza to, ze jest ono w bazie i wystarczy zaktualizowac tresc,
            //jezeli nazwa to np. "1" oznacza, ze trzeba je dodac do bazy.
            $name="Podpunkt_".$x;
            if(!isset($_POST[$name])){
                $tresc = $_POST[$x];
                $file = $base->checkNewFile($_FILES, $x);
                $query = "INSERT INTO elementy ( idFormularza, element, tresc, grafika) VALUES ( $idFormularza, '$name', '$tresc', '$file');";
                $base->iuOperation($query);
            }
            else {
                $tresc = $_POST[$name];
                $query = $base->checkFile($_FILES, $x, $tresc, $idFormularza, $name);
                $base->iuOperation($query);
            }
            $x++;
            $amount--;
        }
    }
    if($typ==0){ $location='konfigurujTest.php';}
    if($typ==1){ $location='konfigurujLekcje.php';}
    if($typ==2){ $location='konfigurujPodsumowanie.php';}
    echo '<script> 
                window.onload=function(){
                    window.open(\''.$location.'\', \'_top\')
                }
                 </script>';
?>