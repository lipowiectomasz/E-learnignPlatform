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
    require dirname(__DIR__) . '/PlatformaELearningowa/vendor/autoload.php';
    use configurator\configurator;

    session_start();

    if($_SESSION['loggedin']!=true){
        $location = "Location: index.php?status=2";
        header($location);
    }
    
    $typ = $_GET['typ'];
    $configurator = new configurator();
    $configurator->generateConfiguratorByType($typ);
    
?>
</body>