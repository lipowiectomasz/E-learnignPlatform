<!DOCTYPE html>
<html lang="pl">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    
    <style>
        *{
            font-family: 'Open Sans', sans-serif;
        }
        body{
            display: flex;
            align-items: center;
            flex-direction: column;
        }
        table{
            border-collapse: collapse;
            width: 60%;
        }
        table,td,th{
            border: 1px solid;
        }
        td,th{
            padding: 10px;
        }
        tr:nth-child(even){
            background-color: #f2f2f2;
        }
        tr:nth-child(odd){
            background-color: #D1D1D1;
        }
        th{
            background-color: #221B20;
            color: white;
        }
        p{
            color: white;
            font-size: 20px;
        }
    </style>
</head>
<body>
    <?php
        require dirname(__DIR__) . '/PlatformaELearningowa/vendor/autoload.php';
        use panels\panelRenderer;
        session_start();
        if($_SESSION['loggedin']!=true){
            $location = "Location: index.php?status=2";
            header($location);
        }
        $user = $_SESSION['user'];
        $logs = new panelRenderer();
    ?>
    echo "<p>Logowania kursantow:</p>";
    echo "<table><tr><th>Login</th><th>Adres ip</th><th>Data</th><th>Godzina</th><th>Przegladarka</th><th>System</th></tr>";
    <?php $logs->makeUsersLogs(); ?>
    </table>
    <p>Logowania podejscia do lekcji:</p>
    <table><tr><th>Login</th><th>Godzina</th><th>Data</th><th>Tytul</th></tr>
    <?php $logs->makeTestsLogs($user); ?>
    </table>
</body>
</html>