<?php
//Mit dieser Funktion wird das mitgegebene Array überprüft, ob
//es sich um ein Array handelt (ein String wäre wirklich nur für
//eine einzelne Person wie z.B. den Dekan sinnvoll). Zudem wird
//überprüft, ob ein "KWM"-Key in diesem Array vorhanden ist und ob
//dieser KWM-Key auch über Werte verfügt.
function checkArray(array|string $currentData):bool{
    if (is_array($currentData)) {
        if (array_key_exists("KWM", $currentData)
            && !empty($currentData["KWM"]))
            return true;
    }
    return false;
}

//Die Funktion ist für die Speicherung vom Array aFH auf das leere
//Array aKWM zuständig. Alle Daten zu KWM werden entsprechend mit
//einer extra Funktion umstrukturiert. Je nachdem, welche Funktion
//das Array gerade betrifft, wird es zum richtigen Key des neuen
//Arrays gespeichert.
function saveData(array|string $data, string $key, array &$aKWM):void{
    $kwm = $data["KWM"];
    $reversedStrings = structureData($kwm, $key);

    if ($key === "Assistenz")
        $aKWM["Superheroes"]= $reversedStrings;
    else
        $aKWM[$key] = $reversedStrings;
}

//Die Funktion ist zur Strukturierung des mitgegebenen Arrays/Strings
//zuständig. Ein Array wird mithilfe des Leerzeichens als
//Separator in Vornamen und Nachnamen geteilt und umgedreht.
//Bei einem String hingegen wird der String mittels des
//Leerzeichens als Separator getrennt und somit ein Array
//erstellt, welches mit der Funktion array_reverse umgedreht wird.
function structureData(array|string $kwm, string $key):array{
    $personArr = [];
    if(is_array($kwm)) {
        foreach($kwm as $key => $person) {
            $person = array_reverse(explode(" ", $person));
            $personArr[$key] = $person;
        }
        return $personArr;
    }
    else {
        return array_reverse(explode(" ", $kwm));
    }
}

//Diese Funktion ist für die Ausgabe des neuen Arrays zuständig. Je nachdem,
//welcher Funktion der Key hergibt, wird die richtige Unterüberschrift
//ausgegeben und eine externe Funktion zur Ausgabe aller Namen aufgerufen.
function printData(array $aKWM):void{
    echo("<h1>Ausgabe von Informationen zum Studiengang KWM:</h1>");
    foreach ($aKWM as $key => $value) {
        echo("<p class='key'>" . $key . "</p>");
        foreach($value as $pos => $value2) {
            writeLines($value, $pos);
        }
    }
}

//Diese Funktion ist zur Ausgabe der einzelnen Namen zuständig.
//Wenn es sich um den Nachnamen handelt, wird ein Beistrich angehängt.
function writeLines(array|string $value, string $pos):void{
    if (is_array($value[$pos]))
        echo("<p>".implode(", ", $value[$pos])."</p>");
    else{
        if($pos == "0")
            echo("<p>".$value[$pos]);
        else
            echo(", ".$value[$pos]."</p>");
    }
}
?>