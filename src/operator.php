<?php
    namespace databaseOperator;

    class operator
    {
        private $base;

        public function __construct()
        {
            require 'dbInfo.php';
            $this->base = new \PDO($dsn, $dbuser, $dbpassword);
        }

        public function checkFile($file, $x, $tresc, $idFormularza, $name){
            if($file['file'.$x]['size']!=0){
                if($file['file'.$x]['error'] == UPLOAD_ERR_OK){
                    $fileName = $uploadLocation.basename($file['file'.$x]['name']);
                    $tempName = $file['file'.$x]['tmp_name'];
                    $typeOfFile = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                    $nameF = "Images/".$fileName;
                    if($typeOfFile != "jpg" && $typeOfFile != "jpeg" && $typeOfFile != "png" ){
                        exit("Niepoprawny format pliku.(Wybierz jpg/jpeg/png)");
                    }
                    if(move_uploaded_file($tempName, $nameF)){
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
            return $query;
        }
        public function checkNewFile($file, $x){
            if($file['file'.$x]['size']!=0){
                if($file['file'.$x]['error'] == UPLOAD_ERR_OK){
                    $fileName = $uploadLocation.basename($file['file'.$x]['name']);
                    $tempName = $file['file'.$x]['tmp_name'];
                    $typeOfFile = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                    $nameF = "Images/".$fileName;
                    if($typeOfFile != "jpg" && $typeOfFile != "jpeg" && $typeOfFile != "png" ){
                        exit("Niepoprawny format pliku.(Wybierz jpg/jpeg/png)");
                    }
                    if(move_uploaded_file($tempName, $nameF)){
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
            return $file;
        }

        public function iuOperation($query){
            $baseOperator = $this->base->prepare($query);
            $baseOperator->execute();
        }
        public function selectRowsOperation($query){
            $rows= array();
            $baseOperator = $this->base->prepare($query);
            $baseOperator->execute();
            while ($row = $baseOperator->fetch()) {
                array_push($rows, $row);
            }
            return $rows;
        }
        public function selectRowOperation($query){
            $baseOperator = $this->base->prepare($query);
            $baseOperator->execute();
            $row = $baseOperator->fetch();
            if(!empty($row)){
				return $row;
			}
        }
    }


