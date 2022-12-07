<?php

    namespace panels;

    use databaseOperator\operator;

    class panelRenderer{

        private $base; 

        public function __construct(){
            $this->base = new operator();
        }


        public function userPanelRenderTests(){
            list($quizzes, $i) = $this->selectForms("0");
            if($i > 1){
                foreach( $quizzes as $t)
                {
                    echo '<div class="navButton"><a href="pokazFormularz.php?podejscie=1&typ=0&id='.$t[1].'&time='.$t[2].'" target="border">'.$t[0].'</a></div>';
                }
            }
        }        

        public function userPanelRenderLessons(){
            list($lessons, $i) = $this->selectForms("1");
            if($i > 1){
                foreach($lessons as $t)
                {
                    echo '<div class="navButton"><a href="pokazFormularz.php?typ=1&id='.$t[1].'" target="border">'.$t[0].'</a></div>';
                }
            }

        }
        public function userPanelRenderSummaries(){
            list($summaries, $i) = $this->selectForms("2");
            if($i > 1){
                foreach($summaries as $t)
                {
                    echo '<div class="navButton"><a href="pokazFormularz.php?typ=2&id='.$t[1].'" target="border">'.$t[0].'</a></div>';
                }
            }
        }
        public function userPanelRenderPriviousTests($user){
            $query = "SELECT podejsciaDoTestu.id AS 'podejscieId', formularze.id ,users.login AS 'login', podejsciaDoTestu.godzina, podejsciaDoTestu.data, formularze.tytul, podejsciaDoTestu.punkty FROM podejsciaDoTestu, users, formularze WHERE users.id = podejsciaDoTestu.idUser AND formularze.id = podejsciaDoTestu.idFormularz AND login='$user';";
            $forms = $this->base->selectRowsOperation($query);
            $tests = array();
            $i = 1;
            foreach($forms as $rekord){
                $id = $rekord['id'];
                $title = $rekord['tytul'];
                $podejscieId = $rekord['podejscieId'];
    
                $tests[$i][0] = $title;
                $tests[$i][1] = $id;
                $tests[$i][2] = $podejscieId;
                $i++;
            } 
            if($i > 1){
                foreach( $tests as $t)
                {
                    echo '<div class="navButton"><a href="pokazFormularz.php?podejscieId='.$t[2].'&podejscie=0&typ=0&id='.$t[1].'" target="border">'.$t[0].'</a></div>';
                }
            }
        }


        public function selectForms($typ){
            if($typ==0){
                $query = "SELECT id, tytul, czas FROM formularze WHERE typ=$typ;";
            }
            else{
                $query = "SELECT id, tytul FROM formularze WHERE typ=$typ;";
            }
            
            $forms = $this->base->selectRowsOperation($query);
            $formsArray = array();
            $i = 1;
            foreach($forms as $form){
                $id = $form['id'];
                $title = $form['tytul'];
                
                $formsArray[$i][0] = $title;
                $formsArray[$i][1] = $id;
                if($typ==0){
                    $czas = $form['czas'];
                    $formsArray[$i][2] = $czas;
                }

                $i++;
            }
            return array($formsArray, $i);
        }

        public function showForm(){
            $idFormularz = $_GET['id'];
            $typ = $_GET['typ'];
            if(isset($_GET['podejscie'])){
                $podejscie = $_GET['podejscie'];
                $podejscieId = $_GET['podejscieId'];
            }
            $idUser = $_SESSION['idUser'];
            $query = "SELECT autor FROM formularze WHERE id=$idFormularz";
            $author = $this->base->selectRowOperation($query);
            $author = $author['autor'];
            echo "<p>Autor: $author</p>";
            if($typ==1 || $typ==2){
                $godzina = date('H:i:s', time());
                $data = date ('Y-m-d', time()); 
            }
            $query = "SELECT id, element, tresc, grafika FROM elementy WHERE idFormularza=$idFormularz;"; 
            $formElements = $this->base->selectRowsOperation($query);
            $amt=0;
            echo '<div><form action="sprawdz.php?id='.$idFormularz.'&amount='.$amt.'" method="post" id="form"><div id="formElements">';
            foreach($formElements as $rekord){
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

                        echo "<h1>$element</h1>";
                        echo '<p>'.$tresc.'<p>';
                        if($grafika!='-'){
                        echo "<img width=\"400\" height=\"200\" src=\"Images/$grafika\"/>";
                        }

                        $query = "SELECT odpowiedz FROM odpowiedziUsera WHERE elementId=$id AND idPodejscia=$podejscieId;";
                        $previousAnswers = $this->base->selectRowsOperation($query);
                        foreach($previousAnswers as $rekordPokazPodejscie){
                            $odpowiedz = $rekordPokazPodejscie['odpowiedz'];
                            $query = "SELECT A, B, C, D, poprawna FROM odpowiedzi WHERE idElement=$id;";
                            $rekordOdp = $this->base->selectRowOperation($query);
                            $warianty = array('A', 'B', 'C', 'D');
                            foreach($warianty as $wariant){
                                $this->makeFormDoneQuestion($wariant, $rekordOdp, $odpowiedz);
                            }

                        }
                    }
                    $amt++;
                }
                elseif($typ==0 && $podejscie==1){
                    if($amt==0){
                        $czas = $_GET['time'];
                        echo '<p>Pozostaly czas:</p>';
                        echo '<p id="timer">'.$czas.'</p>';
                    }
                    echo "<h1>$element</h1>";
                    echo '<p>'.$tresc.'<p>';
                    if($grafika!='-'){
                        echo "<img width=\"400\" height=\"200\" src=\"Images/$grafika\"/>";
                    }
                    if($amt >= 1){
                        $query = "SELECT A, B, C, D, poprawna FROM odpowiedzi WHERE idElement=$id;";
                        $rekordOdp = $this->base->selectRowsOperation($query);
                        $warianty = array('A', 'B', 'C', 'D');
                        foreach($warianty as $wariant){
                            $this->makeFormNewQuestion($rekordOdp, $amt, $wariant);
                        }
                       
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
            echo 
                '<script>
                    var end = document.getElementById("form");
                    end.setAttribute("action", "sprawdz.php?id='.$idFormularz.'&amount='.--$amt.'");
                </script>';

                if($typ==0 && $podejscie==0){
                    $query = "SELECT punkty FROM podejsciaDoTestu WHERE id=$podejscieId;";
                    $rekord = $this->base->selectRowOperation($query);
                    $punkty = $rekord['punkty'];
                    echo "<p>Podsumowanie. Punktacja: $punkty / $amt  </p>";
                    echo '<p><button onclick="window.location.href=\'generujPdf.php?&podejscieId='.$podejscieId.'&typ='.$typ.'&id='.$idFormularz.'\'" type="button">Generuj PDF</button></p>';
                }
                if($typ==0 && $podejscie==1){
                    echo 
                    '</div><p><input type="submit" value="Sprawdz"></p>
                    </form></div>';
                }
        }

        public function makeFormDoneQuestion($wariant, $rekordOdp, $odpowiedz){
            echo "<h5>Odp $wariant:</h5>";
                if($rekordOdp['poprawna']==$wariant){
                    echo '<p style="background-color:green;">'.$rekordOdp[$wariant].'</p>';  
                }
                else {
                    if($odpowiedz==$wariant){
                        echo '<p style="background-color:red;">'.$rekordOdp[$wariant].'</p>';  
                    }
                    else{
                        echo '<p>'.$rekordOdp[$wariant].'</p>';
                    }
                }
        }

        public function makeFormNewQuestion($rekordOdp, $amt, $wariant){
            echo "<h5>Odp $wariant:</h5>";
            echo '<p>'.$rekordOdp[$wariant].'</p>';
            echo '<input type="radio" id="'.$wariant.$amt.'" name="zaznaczona'.$amt.'" value="'.$wariant.'" class="ans">';
            echo '<label for="'.$wariant.$amt.'"></label>';
        }

        public function makeUsersLogs(){
            $query = "SELECT users.login AS 'login', logi.adresIp, logi.data, logi.godzina, logi.przegladarka, logi.systemOp FROM logi, users WHERE users.id = logi.idUser AND users.userType=2;";
            $logs = $this->base->selectRowsOperation($query);
            foreach($logs as $rekord){
                $login = $rekord['login'];
                $adresIp = $rekord['adresIp'];
                $godzina = $rekord['godzina'];
                $data = $rekord['data'];
                $przegladarka = $rekord['przegladarka'];
                $system  = $rekord['systemOp'];
                echo "<tr><td>$login</td><td>$adresIp</td><td>$godzina</td><td>$data</td><td>$przegladarka</td><td>$system</td></tr>";
            }
        }

        public function makeTestsLogs($user){
            $query = "SELECT users.login AS 'login', otwieranieFormularza.godzina, otwieranieFormularza.data, formularze.tytul FROM otwieranieFormularza, users, formularze WHERE users.id = otwieranieFormularza.idUser AND formularze.id = otwieranieFormularza.idFormularz AND formularze.autor = '$user';";
            $logs = $this->base->selectRowsOperation($query);
            foreach($logs as $rekord){
                $login = $rekord['login'];
                $godzina = $rekord['godzina'];
                $data = $rekord['data'];
                $tytul = $rekord['tytul'];
                echo "<tr><td>$login</td><td>$godzina</td><td>$data</td><td>$tytul</td></tr>";
            }

        }   

        public function makeAdminsLogs(){
            $query = "SELECT users.login AS 'login', logi.adresIp, logi.data, logi.godzina, logi.przegladarka, logi.systemOp FROM logi, users WHERE users.id = logi.idUser";
            $logs = $this->base->selectRowsOperation($query);
            foreach($logs as $rekord){
                $login = $rekord['login'];
                $adresIp = $rekord['adresIp'];
                $godzina = $rekord['godzina'];
                $data = $rekord['data'];
                $przegladarka = $rekord['przegladarka'];
                $system  = $rekord['systemOp'];
                echo "<tr><td>$login</td><td>$adresIp</td><td>$godzina</td><td>$data</td><td>$przegladarka</td><td>$system</td></tr>";
            }
        }

        public function makeResultView($user){
            $query = "SELECT podejsciaDoTestu.id AS 'podejscieId', formularze.id ,users.login AS 'login', podejsciaDoTestu.godzina, podejsciaDoTestu.data, formularze.tytul, podejsciaDoTestu.punkty FROM podejsciaDoTestu, users, formularze WHERE users.id = podejsciaDoTestu.idUser AND formularze.id = podejsciaDoTestu.idFormularz AND formularze.autor = '$user';";
            $results = $this->base->selectRowsOperation($query);
            foreach($results as $rekord){
                $podejscieId = $rekord['podejscieId'];
                $id = $rekord['id'];
                $login = $rekord['login'];
                $godzina = $rekord['godzina'];
                $data = $rekord['data'];
                $tytul = $rekord['tytul'];
                $punkty = $rekord['punkty'];
                
                echo "<tr><td>$login</td><td>$godzina</td><td>$data</td><td>$tytul</td><td>$punkty</td><td><a href=\"pokazFormularz.php?podejscieId=$podejscieId&podejscie=0&typ=0&id=$id\">Przejdz</a></td></tr>";
            }
        }
    }