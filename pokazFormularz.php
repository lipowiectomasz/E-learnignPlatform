<head>
    <style>
        *{
            margin: 0;
        }
        body{
            display: flex;
            flex-direction: column;
            align-items: center;
            background-color: #EED6E8;
        }
        #addQuestion{
            display: block;
            position: relative;
            padding: 15px 32px;
            font-size: 15px;
            bottom: 51px;
            right: 5%;
        }
        #go{
            display: inline-block;
            padding: 15px 32px;
            font-size: 15px;
        }
        #addContainer{
            display: flex;
            justify-content: right;
        }
        .setTime{
            padding: 10px 10px;
            font-size: 20px;
        }
        #timer{
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

    $idFormularz = $_GET['id'];
    $typ = $_GET['typ'];
    if(isset($_GET['podejscie'])){
        $podejscie = $_GET['podejscie'];
        $podejscieId = $_GET['podejscieId'];
    }
    $idUser = $_SESSION['idUser'];
    $godzina = date('H:i:s', time());
	$data = date ('Y-m-d', time()); 

    require 'dbInfo.php';
    $polaczenie = mysqli_connect($dbhost, $dbuser, $dbpassword, $dbname);
    mysqli_query($polaczenie, "SET NAMES 'utf8'");

    $query = "SELECT autor FROM formularze WHERE id=$idFormularz";
    $rezultat = mysqli_query($polaczenie, $query) or die ("Blad w pobraniu autora");
    $rekord = mysqli_fetch_array($rezultat);
    echo "<p>Autor: ".$rekord['autor']."</p>";

    if($typ==1 || $typ==2){
        $query = "INSERT INTO otwieranieFormularza (idUser, godzina, data, idFormularz ) VALUES ( $idUser, '$godzina', '$data', $idFormularz)";
        $rezultat = mysqli_query($polaczenie, $query) or die ("Blad we wpisywaniu logow do podejscia do lekcji");
    } 
    
    $query = "SELECT id, element, tresc, grafika FROM elementy WHERE idFormularza=$idFormularz;";
    $rezultat = mysqli_query($polaczenie, $query) or die ("Blad w pobraniu danych formularza $idFormularz");

    $amt=0;

    echo '<div><form action="sprawdz.php?id='.$idFormularz.'&amount='.$amt.'" method="post" id="form"><div id="formElements">';

    while($rekord = mysqli_fetch_array($rezultat)){

        $id = $rekord['id'];
        $element = $rekord['element'];
        $tresc = $rekord['tresc'];
        $grafika = $rekord['grafika'];

        if($typ==0 && $podejscie==0){

            if($amt == 0){
                echo "<h1>$element</h1>";
                echo '<p>'.$tresc.'<p>';
                if($grafika!='-'){
                    echo "<img width=\"400\" height=\"200\" src=\"Images/$grafika\"/>";
                }
            }
            if($amt >= 1){
                
                $queryPokazPodejscie = "SELECT odpowiedz FROM odpowiedziUsera WHERE elementId=$id AND idPodejscia=$podejscieId;";
                $rezultatPokazPodejscie = mysqli_query($polaczenie, $queryPokazPodejscie);
                if ($rezultatPokazPodejscie->num_rows > 0){
                    $rekordPokazPodejscie = mysqli_fetch_array($rezultatPokazPodejscie);
                    echo "<h1>$element</h1>";
                    echo '<p>'.$tresc.'<p>';
                    if($grafika!='-'){
                    echo "<img width=\"400\" height=\"200\" src=\"Images/$grafika\"/>";
                    }

                    $odpowiedz = $rekordPokazPodejscie['odpowiedz'];

                    $queryOdp = "SELECT A, B, C, D, poprawna FROM odpowiedzi WHERE idElement=$id;";
                    $rezultatOdp = mysqli_query($polaczenie, $queryOdp);
                    $rekordOdp = mysqli_fetch_array($rezultatOdp);
                    
                    echo "<h5>Odp A:</h5>";
                    if($rekordOdp['poprawna']=='A'){
                        echo '<p style="background-color:green;">'.$rekordOdp['A'].'</p>';  
                    }
                    else {
                        if($odpowiedz=='A'){
                            echo '<p style="background-color:red;">'.$rekordOdp['A'].'</p>';  
                        }
                        else{
                            echo '<p>'.$rekordOdp['A'].'</p>';
                        }
                    }
 

                    echo "<h5>Odp B:</h5>";
                    if($rekordOdp['poprawna']=='B'){
                        echo '<p style="background-color:green;">'.$rekordOdp['B'].'</p>';  
                    }
                    else {
                        if($odpowiedz=='B'){
                            echo '<p style="background-color:red;">'.$rekordOdp['B'].'</p>';  
                        }
                        else{
                            echo '<p>'.$rekordOdp['B'].'</p>';
                        }
                    }

                    echo "<h5>Odp C:</h5>";
                    if($rekordOdp['poprawna']=='C'){
                        echo '<p style="background-color:green;">'.$rekordOdp['C'].'</p>';  
                    }
                    else {
                        if($odpowiedz=='C'){
                            echo '<p style="background-color:red;">'.$rekordOdp['C'].'</p>';  
                        }
                        else{
                            echo '<p>'.$rekordOdp['C'].'</p>';
                        }
                    }

                    echo "<h5>Odp D:</h5>";
                    if($rekordOdp['poprawna']=='D'){
                        echo '<p style="background-color:green;">'.$rekordOdp['D'].'</p>';  
                    }
                    else {
                        if($odpowiedz=='D'){
                            echo '<p style="background-color:red;">'.$rekordOdp['D'].'</p>';  
                        }
                        else{
                            echo '<p>'.$rekordOdp['D'].'</p>';
                        }
                    }

                }
            }
            
            $amt++;
        }
        elseif($typ==0 && $podejscie==1){
            if($amt==0){
                $czas = $_GET['time'];
                echo '<p>Pozostaly czas:</p>';
                echo '<p id="timer"></p>';
            }
            
            echo "<h1>$element</h1>";
            echo '<p>'.$tresc.'<p>';
            if($grafika!='-'){
                echo "<img width=\"400\" height=\"200\" src=\"Images/$grafika\"/>";
            }
            if($amt >= 1){
        
                $queryOdp = "SELECT A, B, C, D, poprawna FROM odpowiedzi WHERE idElement=$id;";
                $rezultatOdp = mysqli_query($polaczenie, $queryOdp);
                $rekordOdp = mysqli_fetch_array($rezultatOdp);
                
                echo "<h5>Odp A:</h5>";
                echo '<p>'.$rekordOdp['A'].'</p>';
                echo '<input type="radio" id="A'.$amt.'" name="zaznaczona'.$amt.'" value="A" class="ans">';
                echo '<label for="A'.$amt.'"></label>';
                echo "<h5>Odp B:</h5>";
                echo '<p>'.$rekordOdp['B'].'</p>';
                echo '<input type="radio" id="B'.$amt.'" name="zaznaczona'.$amt.'" value="B" class="ans">';
                echo '<label for="B'.$amt.'"></label>';
                echo "<h5>Odp C:</h5>";
                echo '<p>'.$rekordOdp['C'].'</p>';
                echo '<input type="radio" id="C'.$amt.'" name="zaznaczona'.$amt.'" value="C" class="ans">';
                echo '<label for="C'.$amt.'"></label>';
                echo "<h5>Odp D:</h5>";
                echo '<p>'.$rekordOdp['D'].'</p>';
                echo '<input type="radio" id="D'.$amt.'" name="zaznaczona'.$amt.'" value="D" class="ans">';
                echo '<label for="D'.$amt.'"></label>';
    
            }
            $amt++;
        }
        else{
            echo "<h1>$element</h1>";
            echo '<p>'.$tresc.'<p>';
            if($grafika!='-'){
                echo "<img width=\"400\" height=\"200\" src=\"Images/$grafika\"/>";
            }
        }
    }
    echo '<script>
        var end = document.getElementById("form");
        end.setAttribute("action", "sprawdz.php?id='.$idFormularz.'&amount='.--$amt.'");
    </script>';

    if($typ==0 && $podejscie==0){
        $query = "SELECT punkty FROM podejsciaDoTestu WHERE id=$podejscieId;";
        $rezultat = mysqli_query($polaczenie, $query);
        $rekord = mysqli_fetch_array($rezultat);
        $punkty = $rekord['punkty'];
        echo "<p>Podsumowanie. Punktacja: $punkty / $amt  </p>";
        echo '<p><button onclick="window.location.href=\'generujPdf.php?podejscieId='.$podejscieId.'&typ='.$typ.'&id='.$idFormularz.'\'" type="button">Generuj PDF</button></p>';
    }
    mysqli_close($polaczenie);

    if($typ==0 && $podejscie==1){
        echo '</div><p><input type="submit" value="Sprawdz"></p>
                </form></div>';
        echo '<script>
        window.onload=function(){
            watchTime();
        }
        var time='.$czas.';
        function watchTime(){
            var minuty = time/60;
            var sekundy = time%60;
            if(sekundy<10){
                document.getElementById("timer").innerHTML = parseInt(minuty)+":0"+sekundy;
            }
            else{
                document.getElementById("timer").innerHTML = parseInt(minuty)+":"+sekundy;
            }
            if(time==0){
                clearTimeout(t);
                cutOff();
            }
            time=time-1;
            var t = setTimeout(watchTime,1000);
        }
        function cutOff(){
            var answers = document.getElementsByClassName("ans");
            var amt = answers.length;
            amt--;
            while(amt>=0){
                answers[amt].disabled = true;
                amt--;
            }
            alert("Koniec czasu!");
        }
        </script>';
    }
?>
</body>