<?php

//Funktion, welche den vorgegebenen Text je nach Zahl
//(99 - 0) passend ausgibt und auch entsprechende
//CSS Klassen zur Unterscheidung richtig zuordnet.
function writeLine(int $num, int $numMinusOne):void
{
    //Bottles werden fast bis zum Schluss in Mehrzahl geschrieben
    $bottles1 = " bottles ";
    $bottles2 = " bottles ";

    //Falls die letzte Flasche erreicht wurde ändern
    //sich einzelne Textpassagen
    if ($num === 1) {
        $num = "One last ";
        $bottles1 = " bottle ";
        $numMinusOne = "No more ";

        //Falls die vorletzte Flasche erreicht wurde,
        ////Ändert sich die letzte Zeile des Absatzes
    } else if ($numMinusOne === 1) {
        $numMinusOne = "One last ";
        $bottles2 = " bottle ";
    }

    //Speichern des Textes in eine Variable
    $text = $num.$bottles1."of beer on the wall,<br>".
            $num.$bottles1."of beer.<br>
            Take one down, pass it around,<br>".
            $numMinusOne.$bottles2."of beer on the wall.";

    //Falls num eine Zahl ist, d.h. nicht die letzte Zahl ("One last")
    if (is_numeric($num)) {
        if ($num % 2 === 0) { //Gerade oder ungerade Zahl?
            if ($num % 10 === 0) { //Nach jeden Zehnerschritt ein Abstand
                echo("<br>");
            }
            echo("<p class='even'>$text</p>"); //Formatierung für gerade Zahl
        } else {
            echo("<p class='odd'>$text</p>"); //Formatierung für ungerade Zahl
        }
    }
    else {
        echo("<p class='odd'>$text</p>"); //Letzte Flasche - ungerade Zahl
    }
}
?>