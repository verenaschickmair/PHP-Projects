<?php
//Zur Umrechnung der Ganzzahl wird die Ganzzahl sowie der jeweilige
//Umrechnungsfaktor mitgegeben. Es wird ein Array erstellt, welcher
//die auf den Faktor umgerechnete Zahl enthält und am Ende als String
//zurückgegeben

function convertNumber(int $decimal, int $factor):string{
    $number = array();

    //Solange die Ganzzahl größer als 0 ist werden Zahlen
    //an dem Array drangehängt
    while ($decimal > 0) {
        array_push($number, $decimal % $factor); //Rest wird an Array drangehängt
        $decimal = floor($decimal / $factor); //Ganzzahl wird durch Faktor geteilt und abgerundet
    }

    //Am Ende den Array umdrehen, um richtige Ausgabe zu bekommen
    $number = array_reverse($number);

    //"Umwandlung" des Arrays in einen String mittels implode
    return implode('', $number);
}
?>