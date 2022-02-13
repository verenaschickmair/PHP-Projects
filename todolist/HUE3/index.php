<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ToDoList</title>
    <link rel="stylesheet" type="text/css" href="style.css"/>
</head>
<body>
<?php

spl_autoload_register('autoload');
function autoload($sClassname):void{
    require_once($sClassname.".php");
}

try{
    //Neue Benutzer anlegen
    $user = new User("PlannerGirl3000", "Verena Schickmair");
    $user1 = new User("PhpLover1", "Maxi Mustermann");
    //Ein User ist in der TodoListe angemeldet
    $toDoList = new ToDoList($user);

    //Erstellen der unterschiedlichen Einträge
    $entry = new ToDoListItem("e1", new DateTime("2021-02-01"), $user->userId, "Hausarbeit", new DateTime("2021-02-01"));
    $entry1 = new ToDoListItem("e2", new DateTime("2021-04-05"), $user1->userId, "Hausübung machen", new DateTime("2021-04-02"), "Dringend die PHP Hausübung abgeben :D");
    $entry2 = new ToDoListItem("e3", new DateTime("2021-03-06"), $user1->userId, "Yoga", new DateTime("2021-03-02"));
    $entry3 = new ToDoListItem("e4", new DateTime("2021-08-05"), $user->userId, "Prokrastinieren", new DateTime("2021-03-02"), "Ich bin eine Notiz, hallo!");

    //Hinzufügen in die TodoListe
    $toDoList->addEntry($entry);
    $toDoList->addEntry($entry1);
    $toDoList->addEntry($entry2);
    $toDoList->addEntry($entry3);

    //Bearbeiten eines Eintrages (Ändern des Title und des Textes)
    $toDoList->editEntry($entry, "Bearbeiteter Titel", "Bearbeiteter Text");
    $toDoList->editEntry($entry2, "Das funktioniert nicht", "Gehört nicht zu mir");

    //Einen Eintrag abschließen
    $toDoList->finishEntry($entry);

    //Einen Eintrag löschen
    $toDoList->deleteEntry($entry3);
    $toDoList->deleteEntry($entry2);
    $toDoList->deleteEntry($entry);

    //Ausgabe der TodoListe - möglich durch __toString
    echo($toDoList);
}
catch (Exception $e){
    echo("Caught exception: ".$e->getMessage());
}
?>
</body>
</html>

