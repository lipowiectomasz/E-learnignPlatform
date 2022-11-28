<?php

    namespace generator;

    require_once("fpdf/fpdf.php");

    use databaseOperator\operator;

    class pdfGenerator{

        private $base;
        private $pdf;

        public function __construct(){
            $this->base = new operator();
            $this->pdf = new \FPDF('P','mm','A4');
        }

        public function generatePdf(){

            //???????
            session_start();
            $idFormularz = $_GET['id'];
            $typ = $_GET['typ'];
            $podejscieId = $_GET['podejscieId'];
            
            //???????

            ob_start();
            $this->pdf -> AddPage();
            $query = "SELECT id, element, tresc, grafika FROM elementy WHERE idFormularza=$idFormularz;";
            $questions = $this->base->selectRowsOperation($query);

            $amt = 0;

            foreach($questions as $question){
                $id = $question['id'];
                $element = $question['element'];
                $tresc = $question['tresc'];
                $grafika = $question['grafika'];

                if($amt == 0){
                    $this->makeHeaderText($element, $tresc, $grafika);
                }
                if($amt >= 1){
                    $query = "SELECT odpowiedz FROM odpowiedziUsera WHERE elementId=$id AND idPodejscia=$podejscieId;";
                    $odpowiedz = $this->base->selectRowOperation($query);
                    $odpowiedz = $odpowiedz['odpowiedz'];
                    $this->makeQuestionText($element, $tresc, $grafika);

                    $warianty = array('A', 'B', 'C', 'D');
                    $query = "SELECT A, B, C, D, poprawna FROM odpowiedzi WHERE idElement=$id;";
                    $rekordOdp = $this->base->selectRowOperation($query);

                    foreach($warianty as $wariant){
                        $this->makeAnswerText($wariant, $odpowiedz, $rekordOdp);
                    }
                }
                $amt++;
            }
            $query = "SELECT podejsciaDoTestu.punkty, users.login FROM podejsciaDoTestu, users WHERE podejsciaDoTestu.id=$podejscieId AND podejsciaDoTestu.idUser = users.id";
            $pointsData = $this->base->selectRowOperation($query);
            
            $punkty = $pointsData['punkty'];
            $login = $pointsData['login'];
            $this->pdf->SetTextColor(0,0,0);
            $this->pdf->SetFont('Arial', 'B', 11);
            --$amt;
            $this->pdf->Cell(10,10,"Podsumowanie. Punktacja: $punkty / $amt",0,1);
            $this->pdf->Output("$login $tytul","I");
            ob_end_flush();
        }
        private function makeHeaderText($element, $tresc, $grafika){
            $this->pdf->SetFont('Arial', 'B', 15);
            $this->pdf->Cell(20,20,"$element",0,1);
            $this->pdf->SetFont('Arial', '', 11);
            $this->pdf->Cell(10,10,"$tresc",0,1);
            $tytul = $tresc;
            if($grafika!='-'){
                $this->pdf-> Image("Images/$grafika");
            }
        }
        private function makeQuestionText($element, $tresc, $grafika){
            $this->pdf->SetFont('Arial', 'B', 15);
            $this->pdf->Cell(10,10,"$element",0,1);
            $this->pdf->SetFont('Arial', '', 11);
            $this->pdf->Cell(10,10,"$tresc",0,1);
            if($grafika!='-'){
                $this->pdf->Image("Images/$grafika");
            }
        }
        private function makeAnswerText($wariant, $odpowiedz, $rekordOdp){
            $this->pdf->SetTextColor(0,0,0);
            $this->pdf->SetFont('Arial', 'B', 11);
            $this->pdf->Cell(10,10,"Odp $wariant:",0,1);
            $this->pdf->SetFont('Arial', '', 11);
            if($rekordOdp['poprawna']==$wariant){
                $this->pdf->SetTextColor(51,204,51);
                $this->pdf->Cell(10,10,"".$rekordOdp[$wariant]."",0,1);
            }
            else {
                if($odpowiedz==$wariant){
                    $this->pdf->SetTextColor(255,51,0);
                    $this->pdf->Cell(10,10,"".$rekordOdp[$wariant]."",0,1);
                }
                else{
                    $this->pdf->SetTextColor(0,0,0);
                    $this->pdf->Cell(10,10,"".$rekordOdp[$wariant]."",0,1);
                }
            }
            
        }   
        

    }