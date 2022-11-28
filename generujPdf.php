<?php
    require dirname(__DIR__) . '/PlatformaELearningowa/vendor/autoload.php';
    use generator\pdfGenerator;
    $generator = new pdfGenerator();
    $generator->generatePdf();