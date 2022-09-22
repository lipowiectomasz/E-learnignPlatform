<?php
    session_start();
    $user = $_SESSION['user'];
    $idFormularza = $_GET['id'];
    $amount = $_GET['amount'];
    $typ = $_GET['typ'];

    require 'dbInfo.php';
    $polaczenie = mysqli_connect($dbhost, $dbuser, $dbpassword, $dbname);
    mysqli_query($polaczenie, "SET NAMES 'utf8'");
    
    if($typ==0){
        $czas = $_GET['time'];
        if(isset($_GET['new'])&&$_GET['new']==1){
            echo 'Nowy';
            
            //Wstawianie testu
            $tresc = $_POST['Tytul'];
            $query = "INSERT INTO formularze (tytul, typ, autor, czas) VALUES ('$tresc', '$typ', '$user', $czas);";
            $rezultat = mysqli_query($polaczenie, $query) or die ("DB error w wstawianiu testu");

            //Wstawienie tematu
            $tresc = $_POST['Tytul'];
            $query = "INSERT INTO elementy ( idFormularza, element, tresc) VALUES ( $idFormularza, 'Tytul', '$tresc');";
            $rezultat = mysqli_query($polaczenie, $query) or die ("DB error w wstawianiu tematu");

            //Wstawianie pierwszego pytania
            $tresc = $_POST['Pytanie_1'];
            if($_FILES['file1']['size']!=0){
                
                if($_FILES['file1']['error'] == UPLOAD_ERR_OK){
                    
                    $fileName = $uploadLocation.basename($_FILES['file1']['name']);
                    $tempName = $_FILES['file1']['tmp_name'];
                    $typeOfFile = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                    $name = "Images/".$fileName;
                    if($typeOfFile != "jpg" && $typeOfFile != "jpeg" && $typeOfFile != "png" ){
						exit("Niepoprawny format pliku.(Wybierz jpg/jpeg/png)");
					}
                    if(move_uploaded_file($tempName, $name)){
                        echo "Zapisano pomyslnie!<br>";
                    }
                    else{
                        echo "Wystapil problem podczas zapisu!<br>";
                    }
                } else {
                    echo "Wystapil blad przesylu!<br>";
                }	
                $file=$fileName;
            }
            else{
                $file='-';
            }
            $query = "INSERT INTO elementy ( idFormularza, element, tresc, grafika) VALUES ( $idFormularza, 'Pytanie_1', '$tresc', '$file');";
            $rezultat = mysqli_query($polaczenie, $query) or die ("DB error w stawianiu pierwszego pytania");
            $query = "SELECT id FROM elementy WHERE idFormularza = $idFormularza AND element = 'Pytanie_1';";
            $rezultat = mysqli_query($polaczenie, $query);
            $rekord = mysqli_fetch_array($rezultat)or die ("DB error w pobieraniu id do aktualizacji pytania $x"); 
            $idElement = $rekord['id'];

            
            $A=$_POST["A1"];
            $B=$_POST["B1"];
            $C=$_POST["C1"];
            $D=$_POST["D1"];
            $corr=$_POST["correct1"];

            $query = "INSERT INTO odpowiedzi (idElement, A, B, C, D, poprawna) VALUES ($idElement, '$A', '$B', '$C', '$D', '$corr');";
            $rezultat = mysqli_query($polaczenie, $query) or die ("DB error we wstawianiu odpowiedzi do pytania 1"); 
            
        }
        else {
            echo 'Aktualizacja';
            
            //Aktualizacja tytulu testu
            $tresc = $_POST['Tytul'];
            $query = "UPDATE formularze SET tytul = '$tresc', czas = $czas WHERE id = $idFormularza;";
            $rezultat = mysqli_query($polaczenie, $query) or die ("DB error w aktualizacji tytulu testu");
            
            //Aktualizacja tematu
            $tresc = $_POST['Tytul'];
            $query = "UPDATE elementy SET tresc = '$tresc' WHERE idFormularza = $idFormularza AND element = 'Tytul';";
            $rezultat = mysqli_query($polaczenie, $query) or die ("DB error w aktualizacji tytulu");

            //Aktualizacja pierwszego pytania
            $tresc = $_POST['Pytanie_1'];
            if($_FILES['file1']['size']!=0){
                
                if($_FILES['file1']['error'] == UPLOAD_ERR_OK){
                    
                    $fileName = $uploadLocation.basename($_FILES['file1']['name']);
                    $tempName = $_FILES['file1']['tmp_name'];
                    $typeOfFile = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                    $name = "Images/".$fileName;
                    
					if($typeOfFile != "jpg" && $typeOfFile != "jpeg" && $typeOfFile != "png" ){
						exit("Niepoprawny format pliku.(Wybierz jpg/jpeg/png)");
					}
                    if(move_uploaded_file($tempName, $name)){
                        echo "Zapisano pomyslnie!<br>";
                    }
                    else{
                        echo "Wystapil problem podczas zapisu!<br>";
                    }
                } else {
                    echo "Wystapil blad przesylu!<br>";
                }	
                $file=$fileName;
                $query = "UPDATE elementy SET tresc = '$tresc', grafika = '$file' WHERE idFormularza = $idFormularza AND element = 'Pytanie_1';";
            }
            else{
                $file='-';
                $query = "UPDATE elementy SET tresc = '$tresc' WHERE idFormularza = $idFormularza AND element = 'Pytanie_1';";
            }
            $rezultat = mysqli_query($polaczenie, $query) or die ("DB error w aktualizacji pierwszego pytania");

            $query = "SELECT id FROM elementy WHERE idFormularza = $idFormularza AND element = 'Pytanie_1';";
            $rezultat = mysqli_query($polaczenie, $query);
            $rekord = mysqli_fetch_array($rezultat)or die ("DB error w pobieraniu id do aktualizacji pytania $x"); 
            $idElement = $rekord['id'];

                
            $A=$_POST["A1"];
            $B=$_POST["B1"];
            $C=$_POST["C1"];
            $D=$_POST["D1"];
            $corr=$_POST["correct1"];

            $query = "UPDATE odpowiedzi SET A='$A', B='$B', C='$C', D='$D', poprawna='$corr' WHERE idElement = $idElement;";
            $rezultat = mysqli_query($polaczenie, $query) or die ("DB error we aktualizowaniu odpowiedzi do pytania 1"); 
            
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
                if($_FILES['file'.$x]['size']!=0){
                
                    if($_FILES['file'.$x]['error'] == UPLOAD_ERR_OK){
                        
                        $fileName = $uploadLocation.basename($_FILES['file'.$x]['name']);
                        $tempName = $_FILES['file'.$x]['tmp_name'];
                        $typeOfFile = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                        $name = "Images/".$fileName;
                        if($typeOfFile != "jpg" && $typeOfFile != "jpeg" && $typeOfFile != "png" ){
							exit("Niepoprawny format pliku.(Wybierz jpg/jpeg/png)");
						}
                        if(move_uploaded_file($tempName, $name)){
                            echo "Zapisano pomyslnie!<br>";
                        }
                        else{
                            echo "Wystapil problem podczas zapisu!<br>";
                        }
                    } else {
                        echo "Wystapil blad przesylu!<br>";
                    }	
                    $file=$fileName;
                }
                else{
                    $file='-';
                }
                $query = "INSERT INTO elementy ( idFormularza, element, tresc, grafika) VALUES ( $idFormularza, '$name', '$tresc', '$file');";
                $rezultat = mysqli_query($polaczenie, $query) or die ("DB error we wstawianiu pytania $x");

                $query = "SELECT id FROM elementy ORDER BY id DESC LIMIT 1;";
                $rezultat = mysqli_query($polaczenie, $query) or die ("DB error w pobieraniu id ostatniego pytania $x");
                $rekord = mysqli_fetch_array($rezultat);
                $idElement = $rekord['id'];

                $A=$_POST["A".$x];
                $B=$_POST["B".$x];
                $C=$_POST["C".$x];
                $D=$_POST["D".$x];
                $corr=$_POST["correct".$x];

                $query = "INSERT INTO odpowiedzi (idElement, A, B, C, D, poprawna) VALUES ($idElement, '$A', '$B', '$C', '$D', '$corr');";
                $rezultat = mysqli_query($polaczenie, $query)or die ("DB error we wstawianiu odpowiedzi do pytania $x");
            }
            else {
                $tresc = $_POST[$name];
                if($_FILES['file'.$x]['size']!=0){
                
                    if($_FILES['file'.$x]['error'] == UPLOAD_ERR_OK){
                        
                        $fileName = $uploadLocation.basename($_FILES['file'.$x]['name']);
                        $tempName = $_FILES['file'.$x]['tmp_name'];
                        $typeOfFile = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                        $name = "Images/".$fileName;
                        if($typeOfFile != "jpg" && $typeOfFile != "jpeg" && $typeOfFile != "png" ){
							exit("Niepoprawny format pliku.(Wybierz jpg/jpeg/png)");
						}
                        if(move_uploaded_file($tempName, $name)){
                            echo "Zapisano pomyslnie!<br>";
                        }
                        else{
                            echo "Wystapil problem podczas zapisu!<br>";
                        }
                    } else {
                        echo "Wystapil blad przesylu!<br>";
                    }	
                    $file=$fileName;
                    $query = "UPDATE elementy SET tresc = '$tresc', grafika = '$file' WHERE idFormularza = $idFormularza AND element = '$name';";
                }
                else{
                    $file='-';
                    $query = "UPDATE elementy SET tresc = '$tresc' WHERE idFormularza = $idFormularza AND element = '$name';";
                }
                
                $rezultat = mysqli_query($polaczenie, $query)or die ("DB error w aktualizacji pytania $x"); 

                $query = "SELECT id FROM elementy WHERE idFormularza = $idFormularza AND element = '$name';";
                $rezultat = mysqli_query($polaczenie, $query)or die ("DB error w pobieraniu id pytania $x"); 
                $rekord = mysqli_fetch_array($rezultat)or die ("DB error w pobieraniu id do aktualizacji pytania $x"); 
                $idElement = $rekord['id'];

                
                $A=$_POST["A".$x];
                $B=$_POST["B".$x];
                $C=$_POST["C".$x];
                $D=$_POST["D".$x];
                $corr=$_POST["correct".$x];

                $query = "UPDATE odpowiedzi SET A='$A', B='$B', C='$C', D='$D', poprawna='$corr' WHERE idElement = $idElement;";
                $rezultat = mysqli_query($polaczenie, $query) or die ("DB error we aktualizowaniu odpowiedzi do pytania $x"); 
            }
            $x++;
            $amount--;
        }
    }
    if($typ==1 || $typ==2){  
        
            if(isset($_GET['new'])&&$_GET['new']==1){
            echo 'Nowa lekcja';
            
            //Wstawianie lekcji
            $tresc = $_POST['Tytul'];
            $query = "INSERT INTO formularze (tytul, typ, autor) VALUES ('$tresc', '$typ', '$user');";
            $rezultat = mysqli_query($polaczenie, $query) or die ("DB error w wstawianiu lekcji lub podsumowania");

            //Wstawienie tematu
            $tresc = $_POST['Tytul'];
            $query = "INSERT INTO elementy ( idFormularza, element, tresc) VALUES ( $idFormularza, 'Tytul', '$tresc');";
            $rezultat = mysqli_query($polaczenie, $query) or die ("DB error w wstawianiu tematu lekcji lub podsumowania");

            //Wstawianie pierwszego pytania
            $tresc = $_POST['Podpunkt_1'];
            if($_FILES['file1']['size']!=0){
                
                if($_FILES['file1']['error'] == UPLOAD_ERR_OK){
                    
                    $fileName = $uploadLocation.basename($_FILES['file1']['name']);
                    $tempName = $_FILES['file1']['tmp_name'];
                    $typeOfFile = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                    $name = "Images/".$fileName;
                    if($typeOfFile != "jpg" && $typeOfFile != "jpeg" && $typeOfFile != "png" ){
						exit("Niepoprawny format pliku.(Wybierz jpg/jpeg/png)");
					}
                    if(move_uploaded_file($tempName, $name)){
                        echo "Zapisano pomyslnie!<br>";
                    }
                    else{
                        echo "Wystapil problem podczas zapisu!<br>";
                    }
                } else {
                    echo "Wystapil blad przesylu!<br>";
                }	
                $file=$fileName;
            }
            else{
                $file='-';
            }
            $query = "INSERT INTO elementy ( idFormularza, element, tresc, grafika) VALUES ( $idFormularza, 'Podpunkt_1', '$tresc', '$file');";
            $rezultat = mysqli_query($polaczenie, $query) or die ("DB error w stawianiu pierwszego podpunktu lekcji lub podsumowania");
        }
        else {
            echo 'Aktualizacja';

            //Aktualizacja tematu w formularzach
            $tresc = $_POST['Tytul'];
            $query = "UPDATE formularze SET tytul = '$tresc' WHERE id = $idFormularza;";
            $rezultat = mysqli_query($polaczenie, $query) or die ("DB error w aktualizacji tytulu testu");
            
            //Aktualizacja tematu
            $tresc = $_POST['Tytul'];
            $query = "UPDATE elementy SET tresc = '$tresc' WHERE idFormularza = $idFormularza AND element = 'Tytul';";
            $rezultat = mysqli_query($polaczenie, $query) or die ("DB error w aktualizacji tytulu lekcji lub podsumowania");

            //Aktualizacja pierwszego pytania
            $tresc = $_POST['Podpunkt_1'];
            if($_FILES['file1']['size']!=0){
                
                if($_FILES['file1']['error'] == UPLOAD_ERR_OK){
                    
                    $fileName = $uploadLocation.basename($_FILES['file1']['name']);
                    $tempName = $_FILES['file1']['tmp_name'];
                    $typeOfFile = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                    $name = "Images/".$fileName;
                    if($typeOfFile != "jpg" && $typeOfFile != "jpeg" && $typeOfFile != "png" ){
						exit("Niepoprawny format pliku.(Wybierz jpg/jpeg/png)");
					}
                    if(move_uploaded_file($tempName, $name)){
                        echo "Zapisano pomyslnie!<br>";
                    }
                    else{
                        echo "Wystapil problem podczas zapisu!<br>";
                    }
                } else {
                    echo "Wystapil blad przesylu!<br>";
                }	
                $file=$fileName;
                $query = "UPDATE elementy SET tresc = '$tresc', grafika = '$file'WHERE idFormularza = $idFormularza AND element = 'Podpunkt_1';";
            }
            else{
                $file='-';
                $query = "UPDATE elementy SET tresc = '$tresc' WHERE idFormularza = $idFormularza AND element = 'Podpunkt_1';";
            }
            
            $rezultat = mysqli_query($polaczenie, $query) or die ("DB error w aktualizacji pierwszego podpunktu");  
        }
        //Liczba podpunktow do dodania badz do zaktualizowania
        $amount = $amount - 1;
        //Numery podpunktow
        $x=2;
        
        while($amount>0){
            //Jezeli nazwa elementu to np. "Pytanie 1" oznacza to, ze jest ono w bazie i wystarczy zaktualizowac tresc,
            //jezeli nazwa no np. "1" oznacza, ze trzeba je dodac do bazy.
            $name="Podpunkt_".$x;
            if(!isset($_POST[$name])){
                $tresc = $_POST[$x];
                if($_FILES['file'.$x]['size']!=0){
                
                    if($_FILES['file'.$x]['error'] == UPLOAD_ERR_OK){
                        
                        $fileName = $uploadLocation.basename($_FILES['file'.$x]['name']);
                        $tempName = $_FILES['file'.$x]['tmp_name'];
                        $typeOfFile = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                        $name = "Images/".$fileName;
                        if($typeOfFile != "jpg" && $typeOfFile != "jpeg" && $typeOfFile != "png" ){
							exit("Niepoprawny format pliku.(Wybierz jpg/jpeg/png)");
						}
                        if(move_uploaded_file($tempName, $name)){
                            echo "Zapisano pomyslnie!<br>";
                        }
                        else{
                            echo "Wystapil problem podczas zapisu!<br>";
                        }
                    } else {
                        echo "Wystapil blad przesylu!<br>";
                    }	
                    $file=$fileName;
                }
                else{
                    $file='-';
                }
                $query = "INSERT INTO elementy ( idFormularza, element, tresc, grafika) VALUES ( $idFormularza, '$name', '$tresc', '$file');";
                $rezultat = mysqli_query($polaczenie, $query) or die ("DB error we wstawianiu podpunktu $x");
            }
            else {
                $tresc = $_POST[$name];
                if($_FILES['file'.$x]['size']!=0){
                    if($_FILES['file'.$x]['error'] == UPLOAD_ERR_OK){
                        
                        $fileName = $uploadLocation.basename($_FILES['file'.$x]['name']);
                        $tempName = $_FILES['file'.$x]['tmp_name'];
                        $typeOfFile = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                        $name = "Images/".$fileName;
                        if($typeOfFile != "jpg" && $typeOfFile != "jpeg" && $typeOfFile != "png" ){
							exit("Niepoprawny format pliku.(Wybierz jpg/jpeg/png)");
						}
                        if(move_uploaded_file($tempName, $name)){
                            echo "Zapisano pomyslnie!<br>";
                        }
                        else{
                            echo "Wystapil problem podczas zapisu!<br>";
                        }
                    } else {
                        echo "Wystapil blad przesylu!<br>";
                    }	
                    $file=$fileName;
                    $query = "UPDATE elementy SET tresc = '$tresc', grafika = '$file' WHERE idFormularza = $idFormularza AND element = '$name';";
                }
                else{
                    $file='-';
                    $query = "UPDATE elementy SET tresc = '$tresc' WHERE idFormularza = $idFormularza AND element = '$name';";
                }
                $rezultat = mysqli_query($polaczenie, $query)or die ("DB error w aktualizacji podpunktu $x"); 
            }
            $x++;
            $amount--;
        }
    }
    
    mysqli_close($polaczenie);
    if($typ==0){ $location='konfigurujTest.php';}
    if($typ==1){ $location='konfigurujLekcje.php';}
    if($typ==2){ $location='konfigurujPodsumowanie.php';}
    echo '<script> 
                window.onload=function(){
                    window.open(\''.$location.'\', \'_top\')
                }
                 </script>';

    
?>