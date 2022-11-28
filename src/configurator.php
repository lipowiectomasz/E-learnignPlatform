<?php

    namespace configurator;

    use databaseOperator\operator;

    class configurator{

        private $base; 
        
        public function __construct(){
            $this->base = new operator();
        }

        public function generateConfiguratorByType($typ){

            if($typ==0){
                if (isset($_GET['id'])) {
                    $idFormularz = $_GET['id'];
                    $czas = $_GET['time'];
                    
                    $query = "SELECT id, element, tresc, grafika FROM elementy WHERE idFormularza=$idFormularz;";
                    $rows = $this->base->selectRowsOperation($query);

                    $amt = 0;

                    $this->makeConfNav($typ, $idFormularz, $czas, $amt, 0);

                    foreach($rows as $row){
                        $id = $row['id'];
                        $element = $row['element'];
                        $tresc = $row['tresc'];
                        $grafika = $row['grafika'];

                        echo "<h1>$element</h1>";
                        echo '<textarea cols="80" rows="3" name="'.$element.'" class="editor">'.$tresc.'</textarea>';
                        if($grafika!='-'){
                            echo "<img src=\"Images/$grafika\"/>";
                        }
                        if($amt >= 1){
                            echo '<input type="file" name="file'.$amt.'" value="Przekaz plik"/>';
                            $query = "SELECT A, B, C, D, poprawna FROM odpowiedzi WHERE idElement=$id;";
                            $rekordOdp = $this->base->selectRowOperation($query);
                            $corr = $rekordOdp['poprawna'];
                            $warianty = array('A', 'B', 'C', 'D');
                            foreach($warianty as $wariant){
                                $this->makeQuestion($amt, $wariant, $corr, $rekordOdp);
                            }
                        }
                        $amt++;
                    }
                    echo "</div>";
                    echo 
                        '   <p id="sub"><input type="submit" value="Publikuj"></p>
                        </form>
                        <div><p id="addContainer"><button id="addQuestion" onclick="addQuestion(0, '.$idFormularz.')">Dodaj pytanie</button></p></div>';
                }
                else{
                    $idFormularz = $_GET['i'];
                    $this->makeConfNav($typ, $idFormularz, 60, 0, 1);

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
                    
                    echo "</div>";
                    echo '<p id="sub"><input type="submit" value="Publikuj" id="go"></p>
                    </form>';

                    echo '<div><p id="addContainer"><button id="addQuestion" onclick="addQuestion(1)">Dodaj pytanie</button></p></div>';
                }
                echo '<script src="Scripts/addQuestionType0.js"></script> ';
            }
            if($typ==1 || $typ==2){
                if(isset($_GET['id'])){
                    $idFormularz = $_GET['id'];         
                    
                    $query = "SELECT id, element, tresc, grafika FROM elementy WHERE idFormularza=$idFormularz;";
                    $rows = $this->base->selectRowsOperation($query);

                    $amt = 0;

                    echo '<div><form action="zapiszDoBazy.php?typ='.$typ.'&id='.$idFormularz.'&amount='.$amt.'" method="post" enctype="multipart/form-data" id="form"><div id="formElements">';

                    foreach($rows as $row){

                        $id = $row['id'];
                        $element = $row['element'];
                        $tresc = $row['tresc'];
                        $grafika = $row['grafika'];

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
                    echo '</div><p id="sub"><input type="submit" value="Publikuj" id="go"></form></p><p id="addContainer"><button id="addQuestion" onclick="addQuestion(0, '.$idFormularz.', '.$typ.')">Dodaj podpunkt</button></p>
                    </div>';
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
                    </form><p id="addContainer"><button id="addQuestion" onclick="addQuestion(1)">Dodaj podpunkt</button></p>';
                }
                echo '<script src="Scripts/addQuestionType1.js"></script> ';
            }
        }
        private function makeQuestion($amt, $wariant, $corr, $rekordOdp){
            echo "<h5>Odp $wariant:</h5>";
            echo '<textarea cols="80" rows="3" name="'.$wariant.$amt.'">'.$rekordOdp[$wariant].'</textarea>';
            echo '<input type="radio" id="'.$wariant.$amt.'" name="correct'.$amt.'" value="'.$wariant.'" ';
            if($corr==$wariant){echo 'checked="checked"';}
            echo '>';
            echo '<label for="'.$wariant.$amt.'">Poprawna</label>';
        }
        private function makeConfNav($typ, $idFormularz, $czas = 60, $amt = 0, $new = 0){
            if($new == 0){
                $formLink = "zapiszDoBazy.php?typ=$typ&new=$new&id=$idFormularz&amount=$amt&time=$czas";
            }
            else{
                $formLink = "zapiszDoBazy.php?typ=$typ&id=$idFormularz&time=60&new=$new&amount=1";
            }

            echo 
            '<div>
                <div>
                    <p class="setTime">
                        Czas na wykonanie testu w sekundach: 
                        <button class="setTime" onclick="setTime(-30,1)">-</button>
                        <span id="timer">'. $czas .'</span>
                        <button class="setTime"onclick="setTime(30,1)">+</button>
                    </p>
                </div>
                <form action="'.$formLink.'" method="post" enctype="multipart/form-data" id="form">
                <div id="formElements">'; 
        }
        
        public function generateTestsConfigurator($user){
            $query = "SELECT id, tytul, czas FROM formularze WHERE typ=0 AND autor='$user';";
            $quizzes = $this->base->selectRowsOperation($query);
            list($tests, $i) = $this->takeTestsArray($quizzes);
            $this->listTests($tests, $i);
            $this->addNewButton("0");
        }

        public function generateLessonConfigurator($user){
            $query = "SELECT id, tytul FROM formularze WHERE typ='1' AND autor='$user';";
            $lessons = $this->base->selectRowsOperation($query);
            list($tests, $i) = $this->takeElementsArray($lessons);
            $this->listForms($tests, $i, "1", "lekcje");
            $this->addNewButton("1");
        }

        public function generateSummaryConfigurator($user){
            $query = "SELECT id, tytul FROM formularze WHERE typ='2' AND autor='$user';";
            $summarys = $this->base->selectRowsOperation($query);
            list($tests, $i) = $this->takeElementsArray($summarys);
            $this->listForms($tests, $i, "2", "podsumowania");
            $this->addNewButton("2");
        }

        public function takeElementsArray($elements){
            $tests = array();
            $i = 1;
            foreach($elements as $element){
                $id = $element['id'];
                $title = $element['tytul'];

                $tests[$i][0] = $title;
                $tests[$i][1] = $id;
                $i++;
            }
            return array($tests, $i);
        }
        
        public function takeTestsArray($elements){
            $tests = array();
            $i = 1;
            foreach($elements as $element){
                $id = $element['id'];
                $title = $element['tytul'];
                $czas = $element['czas'];

                $tests[$i][0] = $title;
                $tests[$i][1] = $id;
                $tests[$i][2] = $czas;
                $i++;
            }
            return array($tests, $i);
        }

        public function listForms($tests, $i, $typ, $rodzaj){
            //Lekcje
            if($i > 1){
                echo "<p>Twoje $rodzaj: </p>";
                foreach( $tests as $t)
                {
                    echo '<div class="navButtonSplit">
                        <a href="konfiguruj.php?typ='.$typ.'&id='.$t[1].'" target="border">'.$t[0].'</a>
                        <a href="usunFormularz.php?dec=0&typ='.$typ.'&id='.$t[1].'" target="border">Usun</a>
                    </div>';
                }
            }
            //Podsumowania
        }

        public function listTests($tests, $i){
            if($i > 1){
                echo "<p>Twoje testy: </p>";
                foreach( $tests as $t)
                {
                    echo '<div class="navButtonSplit"><a href="konfiguruj.php?typ=0&id='.$t[1].'&time='.$t[2].'" target="border">'.$t[0].'</a>
                    <a href="usunFormularz.php?dec=0&typ=0&id='.$t[1].'" target="border">Usun</a>
                    </div>';
                }
            }
        }

        public function addNewButton($typ){
            $query = "INSERT INTO formularze (tytul, typ, autor, czas) VALUES ('0', '4', '0', 0);";
            $this->base->iuOperation($query);
            $query = "SELECT id FROM formularze ORDER BY id DESC LIMIT 1;";
            $lastIndex = $this->base->selectRowOperation($query);
            $lastIndex = $lastIndex['id'];
            $lastIndex=intval($lastIndex)+1;
            $query = "DELETE FROM `formularze` WHERE typ=4;";
            $this->base->iuOperation($query);
            echo '
            <div class="navButton"><a href="konfiguruj.php?new=1&typ='.$typ.'&i='.$lastIndex.'" target="border">Dodaj</a></div>
            <div class="navButton"><a href="panelSzkoleniowiec.php">Wróć</a></div>
            </div>';
        }   

    }