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

        require 'dbInfo.php';
        $typ = $_GET['typ'];
    
        if($typ==0){

            if(isset($_GET['id'])){
                $idFormularz = $_GET['id'];
                $czas = $_GET['time'];
                
                $polaczenie = mysqli_connect($dbhost, $dbuser, $dbpassword, $dbname);
                mysqli_query($polaczenie, "SET NAMES 'utf8'");

                $query = "SELECT id, element, tresc, grafika FROM elementy WHERE idFormularza=$idFormularz;";

                $rezultat = mysqli_query($polaczenie, $query);

                $amt=0;
                echo '<div>
                            <div>
                                <p class="setTime">
                                    Czas na wykonanie testu w sekundach: 
                                    <button class="setTime" onclick="setTime(-30,1)">-</button>
                                    <span id="timer">'. $czas .'</span>
                                    <button class="setTime"onclick="setTime(30,1)">+</button>
                                </p>
                            </div>
                            <form action="zapiszDoBazy.php?typ='.$typ.'&new=0&id='.$idFormularz.'&amount='.$amt.'&time='.$czas.'" method="post" enctype="multipart/form-data" id="form">
                                <div id="formElements">';
        
                while($rekord = mysqli_fetch_array($rezultat)){
                    $id = $rekord['id'];
                    $element = $rekord['element'];
                    $tresc = $rekord['tresc'];
                    $grafika = $rekord['grafika'];

                    echo "<h1>$element</h1>";
                    echo '<textarea cols="80" rows="3" name="'.$element.'" class="editor">'.$tresc.'</textarea>';
                    if($grafika!='-'){
                        echo "<img src=\"Images/$grafika\"/>";
                    }
                    if($amt >= 1){
                        echo '<input type="file" name="file'.$amt.'" value="Przekaz plik"/>';
                        //$dbhost="mysql01.tomlip002.beep.pl"; $dbuser="tomlip009"; $dbpassword="Kolory12"; $dbname="zad9db";
                        $polOdp = mysqli_connect($dbhost, $dbuser, $dbpassword, $dbname);
            
                        $queryOdp = "SELECT A, B, C, D, poprawna FROM odpowiedzi WHERE idElement=$id;";
                        $rezultatOdp = mysqli_query($polOdp, $queryOdp);
                        $rekordOdp = mysqli_fetch_array($rezultatOdp);
                        $corr = $rekordOdp['poprawna'];

                        echo "<h5>Odp A:</h5>";
                        echo '<textarea cols="80" rows="3" name="A'.$amt.'">'.$rekordOdp['A'].'</textarea>';
                        echo '<input type="radio" id="A'.$amt.'" name="correct'.$amt.'" value="A" ';
                        if($corr=='A'){echo 'checked="checked"';}
                        echo '>';
                        echo '<label for="A'.$amt.'">Poprawna</label>';
                        echo "<h5>Odp B:</h5>";
                        echo '<textarea cols="80" rows="3" name="B'.$amt.'">'.$rekordOdp['B'].'</textarea>';
                        echo '<input type="radio" id="B'.$amt.'" name="correct'.$amt.'" value="B" ';
                        if($corr=='B'){echo 'checked="checked"';}
                        echo '>';
                        echo '<label for="B'.$amt.'">Poprawna</label>';
                        echo "<h5>Odp C:</h5>";
                        echo '<textarea cols="80" rows="3" name="C'.$amt.'">'.$rekordOdp['C'].'</textarea>';
                        echo '<input type="radio" id="C'.$amt.'" name="correct'.$amt.'" value="C" ';
                        if($corr=='C'){echo 'checked="checked"';}
                        echo '>';
                        echo '<label for="C'.$amt.'">Poprawna</label>';
                        echo "<h5>Odp D:</h5>";
                        echo '<textarea cols="80" rows="3" name="D'.$amt.'">'.$rekordOdp['D'].'</textarea>';
                        echo '<input type="radio" id="D'.$amt.'" name="correct'.$amt.'" value="D" ';
                        if($corr=='D'){echo 'checked="checked"';}
                        echo '>';
                        echo '<label for="D'.$amt.'">Poprawna</label>';

                        mysqli_close($polOdp);
                    }
                    $amt++;

                } 
                mysqli_close($polaczenie);
                echo '      <p id="sub"><input type="submit" value="Publikuj"></p>
                            </form>
                            <p id="addContainer"><button id="addQuestion" onclick="addQuestion(1)">Dodaj pytanie</button></p>
                        </div>';
            }
            else{
                $idFormularz = $_GET['i'];
                echo '<div>
                <div>
                    <p class="setTime">
                        Czas na wykonanie testu w sekundach: 
                        <button class="setTime" onclick="setTime(-30,1)">-</button>
                        <span id="timer"> 60 </span>
                        <button class="setTime"onclick="setTime(30,1)">+</button>
                    </p>
                </div>
                <form action="zapiszDoBazy.php?typ='.$typ.'&id='.$idFormularz.'&time=60" method="post" id="form" enctype="multipart/form-data">
                <div id="formElements">';
            
                echo "<h1>Tytul</h1>";
                echo '<textarea cols="80" rows="3" name="Tytul" class="editor">Tytul</textarea>';

                echo "<h1>Pytanie_1</h1>";
                echo '<textarea cols="80" rows="3" name="Pytanie_1" class="editor">Pytanie_1 tekst</textarea>';
                echo '<input type="file" name="file1" value="Przekaz plik"/>';
                echo "<h5>Odp A:</h5>";
                echo '<textarea cols="80" rows="3" name="A1"></textarea>
                    <input type="radio" id="A1" name="correct1" value="A">
                  	<label for="A1">Poprawna</label>';

                echo "<h5>Odp B:</h5>";
                echo '<textarea cols="80" rows="3" name="B1"></textarea>
                    <input type="radio" id="B1" name="correct1" value="B">
                    <label for="B1">Poprawna</label>';

                echo "<h5>Odp C:</h5>";
                echo '<textarea cols="80" rows="3" name="C1"></textarea>
                    <input type="radio" id="C1" name="correct1" value="C">
              	    <label for="C1">Poprawna</label>';

                echo "<h5>Odp D:</h5>";
                echo '<textarea cols="80" rows="3" name="D1"></textarea>
                    <input type="radio" id="D1" name="correct1" value="D">
                  	<label for="D1">Poprawna</label>';
                

                echo '</div><p id="sub"><input type="submit" value="Publikuj" id="go"></p>
                </form><p id="addContainer"><button id="addQuestion" onclick="addQuestion(1)">Dodaj pytanie</button></p>
                </div>';
            }

            echo '<script>
            var time=60;
            
            function addQuestion(n){
                var x = document.getElementsByClassName("editor");      //Pobranie listy wszystkich pytan
                i = x.length;                                       //Zapisanie ilosci pytan

                var end = document.getElementById("form");              //Dodanie nowego naglowka i pytania przed przyciskiem "Publikuj"
                var elements = document.getElementById("formElements");
                var elementsContent = elements.innerHTML;
                elementsContent += "<h1>Pytanie_"+i+"</h1><textarea cols=\"80\" rows=\"3\" name=\"Pytanie_"+i+"\" class=\"editor\">Pytanie_"+i+" tekst</textarea><input type=\"file\" name=\"file"+i+"\" value=\"Przekaz plik\"><h5>Odp A:</h5><textarea cols=\"80\" rows=\"3\" name=\"A"+i+"\"></textarea><input type=\"radio\" id=\"A"+i+"\" name=\"correct"+i+"\" value=\"A\">&nbsp;<label for=\"A"+i+"\">Poprawna</label><h5>Odp B:</h5><textarea cols=\"80\" rows=\"3\" name=\"B"+i+"\"></textarea><input type=\"radio\" id=\"B"+i+"\" name=\"correct"+i+"\" value=\"B\">&nbsp;<label for=\"B"+i+"\">Poprawna</label><h5>Odp C:</h5><textarea cols=\"80\" rows=\"3\" name=\"C"+i+"\"></textarea><input type=\"radio\" id=\"C"+i+"\" name=\"correct"+i+"\" value=\"C\">&nbsp;<label for=\"C"+i+"\">Poprawna</label><h5>Odp D:</h5><textarea cols=\"80\" rows=\"3\" name=\"D"+i+"\"></textarea><input type=\"radio\" id=\"D"+i+"\" name=\"correct"+i+"\" value=\"D\">&nbsp;<label for=\"D"+i+"\">Poprawna</label>";

                elements.innerHTML = elementsContent;
                
                end.setAttribute("action", "zapiszDoBazy.php?typ=0&new="+n+"&id=6&amount="+i+"&time="+time);
            }
            function setTime(s, n){
                time = time + s;
                if(time<60){
                    time=60;
                }
                else if(time>3600){
                    time=3600;
                }
                var x = document.getElementsByClassName("editor");      //Pobranie listy wszystkich pytan
                i = x.length;                                           //Zapisanie ilosci pytan
                var end = document.getElementById("form");
                end.setAttribute("action", "zapiszDoBazy.php?typ='.$typ.'&new="+n+"&id='.$idFormularz.'&amount="+i+"&time="+time);

                var timer = document.getElementById("timer");
                timer.innerHTML = time;
            }
            </script> ';
        
        }

    if($typ==1 || $typ==2){
            if(isset($_GET['id'])){
                
                $idFormularz = $_GET['id'];
                $polaczenie = mysqli_connect($dbhost, $dbuser, $dbpassword, $dbname);
                mysqli_query($polaczenie, "SET NAMES 'utf8'");
            
                $query = "SELECT id, element, tresc, grafika FROM elementy WHERE idFormularza=$idFormularz;";
    
                $rezultat = mysqli_query($polaczenie, $query)or die("Problem z pobraniem formularza z bazy");
    
                $amt=0;
                
                
                echo '<div><form action="zapiszDoBazy.php?typ='.$typ.'&id='.$idFormularz.'&amount='.$amt.'" method="post" enctype="multipart/form-data" id="form"><div id="formElements">';
    
                while($rekord = mysqli_fetch_array($rezultat)){
                    $id = $rekord['id'];
                    $element = $rekord['element'];
                    $tresc = $rekord['tresc'];
                    $grafika = $rekord['grafika'];

                    echo "<h1>$element</h1>";
                    echo '<textarea cols="80" rows="10" name="'.$element.'" class="editor">'.$tresc.'</textarea>';
                    if($grafika!='-'){
                        echo "<img src=\"Images/$grafika\"/>";
                    }
                    if($element != "Tytul"){
                        echo '<input type="file" name="file'.$amt.'" value="Przekaz plik"/>';
                    }
                    
                    $amt++;
                } 
                mysqli_close($polaczenie);
                echo '</div><p id="sub"><input type="submit" value="Publikuj" id="go"></p><p id="addContainer"><button id="addQuestion" onclick="addQuestion(1)">Dodaj podpunkt</button></p>
                        </form></div>';
            }
            else{
                $idFormularz = $_GET['i'];
                
                echo '<form action="zapiszDoBazy.php?new=1&typ='.$typ.'&id='.$idFormularz.'" method="post" id="form" enctype="multipart/form-data"><div id="formElements">';
            
                echo "<h1>Tytul</h1>";
                echo '<textarea cols="80" rows="3" name="Tytul" class="editor">Tytul</textarea>';
    
                echo "<h1>Podpunkt_1</h1>";
                echo '<textarea cols="80" rows="3" name="Podpunkt_1" class="editor">Podpunkt_1 tekst</textarea>';
                echo '<input type="file" name="file1" value="Przekaz plik"/>';
    
                echo '</div><p id="sub"><input type="submit" value="Publikuj" id="go"></p>
                </form><p id="addContainer"><button id="addQuestion" onclick="addQuestion(1)">Dodaj podpunkt</button></p></div>';
            }
            echo '<script>
            function addQuestion(n){
                var x = document.getElementsByClassName("editor");      //Pobranie listy wszystkich pytan
                var i = x.length;                                       //Zapisanie ilosci pytan

                var end = document.getElementById("form");              //Dodanie nowego naglowka i pytania przed przyciskiem "Publikuj"

                var elements = document.getElementById("formElements");
                var elementsContent = elements.innerHTML;
                elementsContent += "<h1>Podpunkt_"+i+"</h1><textarea cols=\"80\" rows=\"3\" name=\"Podpunkt_"+i+"\" class=\"editor\">Podpunkt_"+i+" tekst</textarea><input type=\"file\" name=\"file"+i+"\" value=\"Przekaz plik\"/>";

                elements.innerHTML = elementsContent;

                end.setAttribute("action", "zapiszDoBazy.php?typ='.$typ.'&new="+n+"&id='.$idFormularz.'&amount="+i);
            }
            </script> ';
    }
?>
</body>