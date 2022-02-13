<?php


class TodoListItem
{
    public function __construct(
        private string $entryId,
        private string $creationDate,
        private string $creatorId,
        private string $title,
        private mixed $editDate,
        private string $text,
        private string $status,
        private mixed $editorId
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
        $result = "\n<div id='".$this->entryId."' class='entry'>
            <div class='status'>Status: ".$this->status."</div>
            <h3 class='title'>Titel: ".$this->title."</h3>";

        if($this->text != null)
            $result .= "<p class='note'>".$this->text."</p>";

        $result .= "<p>Ersteller-ID: ".$this->creatorId."</p>
            <p>Erstellungsdatum: ".$this->creationDate."</p>";

        //Erst wenn eine Bearbeitung stattfand soll auch das
        // Bearbeitungsdatum und die ID des Bearbeiters angezeigt werden
        if($this->editorId != null) {
            $result .= "<p>Letzter Bearbeiter-ID: " . $this->editorId . "</p>";
            $result .= "<p>Letztes Bearbeitungsdatum: " . $this->editDate . "</p>";
        }

        $result .= "<div class='buttons'>\n";
        if($this->status != "abgeschlossen") {
            $result .= "<input type='hidden' name='action' value='edit'>";
            $result .= "<a href='index.php?action=edittask&entryid=".$this->entryId."' class='edit' data-toogle='modal' data-target='#editModal'>Bearbeiten</a>";
            $result .= "<input type='hidden' name='action' value='finish'>";
            $result .= "<a href='index.php?action=finishtask&entryid=" . $this->entryId . "' class='finish'>Abschließen</a>";
        }
        else{
            $result .= "<input type='hidden' name='action' value='open'>";
            $result .= "<a href='index.php?action=opentask&entryid=" . $this->entryId . "' class='finish'>Aktivieren</a>";
        }
        $result .= "<input type='hidden' name='action' value='delete'>";
        $result .= "<a href='index.php?action=deletetask&entryid=".$this->entryId."' class='delete'>Löschen</a>";
        $result .= "\n</div>\n</div>";
        return $result;
    }

}