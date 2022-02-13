<?php

//Aufgabe 1: Erzeugen des Arrays $aFH, welches der Struktur 1 der
//Angabe folgt inkl. Befüllung der Daten in das Array.
$aFH = array(
    "Dekan" => "Berthold Kerschbaumer",
    "Studiengangsleiter" => array(
        "MTD" => "Wilhelm Burger",
        "KWM" => "Josef Altmann",
        "SE" => "Heinz Dobler"
    ),
    "Assistenz" => array(
        "MTD" => array(
            "Irmgard Deibl",
            "Sabrina König"
        ),
        "KWM" => array(
            "Elke Ortner",
            "Karin Kocher"
        ),
        "SE" =>  array(
            "Renate Haghofer",
            "Birgit Haider"
        )
    ),
    "Profs" => array(
        "MTD" => array(
            "Wilhelm Burger",
            "usw"
        ),
        "KWM"=> array(
            "Josef Altmann",
            "Mirjam Augstein",
            "Tanja Jadin",
            "Christina Ortner",
            "Johannes Schönböck"
        )
    )
);

//Aufgabe 2: Erstellen eines leeren Arrays $aKWM
$aKWM = array();

?>