<!doctype html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>HÜ 2 - PHP Arrays</title>
    <link rel="stylesheet" type="text/css" href="style.css"/>
</head>
<body>
<?php
require_once "data.php";
require_once "functions.php";

//Aufgabe 3: relevante Daten aus $aFH auslesen
foreach($aFH as $key => $currentData) {
    if (checkArray($currentData)) {
        //Aufgabe 4: Benötigte Daten aus $aFH in $aKWM speichern
        saveData($currentData, $key, $aKWM);
    }
}

//Aufgabe 5: Alle Daten im neuen Array $aKWM gut formatiert ausgeben
printData($aKWM);

?>
</body>
</html>
