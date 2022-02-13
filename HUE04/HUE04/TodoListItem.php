<?php


class TodoListItem
{
    public function __construct(
        private string $entryId,
        private DateTime $creationDate,
        private string $creatorId,
        private string $title,
        private DateTime $editDate,
        private string $text = "",
        private string $status = "aktiv",
        private string $editorId = "",
    )
    {
    }

    //Getter
    public function __get(string $entry):mixed
    {
        if (property_exists('TodoListItem', $entry)) {
            return $this->{$entry};
        } else throw new Exception("Attribute " . $entry . " does not exist in class TodoListItem!");
    }

    //Setter
    public function __set(string $entry, mixed $mValue):void
    {
        if (property_exists('TodoListItem', $entry)) {
            $this->{$entry} = $mValue;
        } else throw new Exception("Attribute " . $entry . " does not exist in class TodoListItem!");
    }

    //Magic Method __toString() für die Ausgabe der
    //einzelnen Einträge in der TodoListe
    public function __toString():string
    {
        $result = "\n<div id='.$this->entryId.' class='entry'>
            <div class='status'>Status: ".$this->status."</div>
            <h3 class='title'>Titel: ".$this->title."</h3>";

        if($this->text != "")
            $result .= "<p class='note'>".$this->text."</p>";

        $result .= "<p>Ersteller: ".$this->creatorId."</p>
            <p>Erstellungsdatum: ".$this->creationDate->format("d.m.Y")."</p>";

        //Erst wenn eine Bearbeitung stattfand soll auch das
        // Bearbeitungsdatum und die ID des Bearbeiters angezeigt werden
        if($this->editorId != "") {
            $result .= "<p>Letzter Bearbeiter: " . $this->editorId . "</p>";
            $result .= "<p>Letztes Bearbeitungsdatum: " . $this->editDate->format("d.m.Y") . "</p>";
        }
        return $result;
    }
}