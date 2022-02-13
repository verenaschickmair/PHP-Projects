<!doctype html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>HÜ 1 - Übung 2</title>
    <link rel="stylesheet" type="text/css" href="style.css"/>
</head>
<body>
<?php
require_once "util.php";

//Vordefinierte Ganzzahl
$number = 3492;

//Die obige Dezimalzahl wird mit allen Umrechnungsfaktoren
//(2 - 10) berechnet und entsprechend ausgegeben
for ($i = 2; $i <= 10; $i++) {
    echo("<p>The decimal ".$number." denoted to the base of ".$i." is ".convertNumber($number, $i).".</p>");
}
?>
</body>
</html>
