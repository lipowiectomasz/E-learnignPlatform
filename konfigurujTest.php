<!DOCTYPE html>
<html lang="pl">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <style>
        *{
            margin: 0;
            font-family: 'Open Sans', sans-serif;
        }
        body{
            height: 100%; 
            width: 100%;
        }
        header{
            width: 100%;
            height: 10vh;
            background-color: #3B3339;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        header p{
            color:#FFA9E9;
            font-size: 35px;
            /* text-align: center; */
        }
        #mainContainer{
            width: 100%;
            height: 100vh;
            display: flex;
        }
        #nav{
            /* height: 500px;
            width: 300px; */
            height: 100%;
            width: 20%;
            background-color: #554751;
            display: flex;
            flex-direction: column;
            font-size: 25px;
        }
        #main{
            /* height: 500px;
            width: 1200px; */
            height: 100%;
            width: 80%;
            background-color: #6E5A69;
        }
        #nav p:first-child{
            font-weight: bold;
        }
        #nav p{
            height: 100px;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: default;
        }
        .navButton, .navButtonSplit{
            width: 100%;
            height: 100px;
            background-color: #483D45;
            margin: 1% 0 1% 0;
        }
        .navButtonSplit{
            /* margin: 3px 0 3px 0; */
            border: 1px;
            border-top-style: solid;
            border-bottom-style: solid;
        }
        .navButtonSplit a{
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            height: 50%;
            text-decoration: none;
            color: black;
        }
        .navButton a{
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            height: 100%;
            text-decoration: none;
            color: black;
        }
        .navButton a:hover, .navButtonSplit a:hover{
            background-color: #3B3339;
            color: white;
        }
    </style>
    <title>Tomasz Lipowiec</title>
</head>
<body>
    <header>
        <p>Konfigurator testów</p>
    </header>
    <?php
        session_start();

        if($_SESSION['loggedin']!=true){
            $location = "Location: index.php?status=2";
            header($location);
        }

        $user = $_SESSION['user'];

        require 'dbInfo.php';
        $polaczenie = mysqli_connect($dbhost, $dbuser, $dbpassword, $dbname);
        mysqli_query($polaczenie, "SET NAMES 'utf8'");

        $query = "SELECT id, tytul, czas FROM formularze WHERE typ=0 AND autor='$user';";
        $rezultat = mysqli_query($polaczenie, $query) or die ("Problem with downloading tests");

        $tests = array();
        $i = 1;
        if ($rezultat->num_rows > 0){
            while($rekord = mysqli_fetch_array($rezultat)){
                $id = $rekord['id'];
                $title = $rekord['tytul'];
                $czas = $rekord['czas'];

                $tests[$i][0] = $title;
                $tests[$i][1] = $id;
                $tests[$i][2] = $czas;
                $i++;
            } 
        }
        echo '
        <section id="mainContainer">
            <div id="nav">
                <p> Zalogowano - '.$user.' </p>
                <div class="navButton"><a href="wyloguj.php">Wyloguj</a></div>
                ';
                    if($i > 1){
                        echo "<p>Twoje testy: </p>";
                        foreach( $tests as $t)
                        {
                            echo '<div class="navButtonSplit"><a href="konfiguruj.php?typ=0&id='.$t[1].'&time='.$t[2].'" target="border">'.$t[0].'</a>
                            <a href="usunFormularz.php?dec=0&typ=0&id='.$t[1].'" target="border">Usun</a>
                            </div>';
                        }
                    }
                    $query = "SELECT id FROM formularze ORDER BY id DESC LIMIT 1;";
                    $rezultat = mysqli_query($polaczenie, $query) or die ("Niepowodzenie w pobraniu ostatniego indeksu formularza");
                    $lastIndex=mysqli_fetch_array($rezultat);
                    $lastIndex=$lastIndex['id']+1;
        echo '
                <div class="navButton"><a href="konfiguruj.php?typ=0&i='.$lastIndex.'" target="border">Dodaj nowy</a></div>
                <div class="navButton"><a href="panelSzkoleniowiec.php">Wroc</a></div>
            </div>
        
            <div id="main">
                <iframe name="border" width="100%" height="100%" style="border:none;">
                </iframe>
            </div>
        </section>';
        mysqli_close($polaczenie);
    ?>
</body> 
</html>
