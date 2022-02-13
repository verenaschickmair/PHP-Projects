<?php


class TodoList
{
    public function __construct(
        private User $loginUser,
        private array $entries = []
    ) {
    }

    //Getter
    public function __get(string $list):mixed
    {
        if (property_exists('TodoList', $list)) {
            return $this->{$list};
        } else throw new Exception("Attribute " . $list . " does not exist in class TodoList!");
    }

    //Setter
    public function __set(string $list, mixed $value):void
    {
        if (property_exists('TodoList', $list)) {
            $this->{$list} = $value;
        } else throw new Exception("Attribute " . $list . " does not exist in class TodoList!");
    }

    //Ein neuer Entry kann von jedem erstellt werden. Jedoch darf
    //der die Entry-ID noch nicht existieren.
    public function addEntry(TodoListItem $entry):void
    {
        if (!array_key_exists($entry->entryId, $this->entries))
            $this->entries[$entry->entryId] = $entry;
        else throw new Exception("Entry " . $entry->entryId . " does not exist!");
    }

    //Es ist nur für den Ersteller möglich, seine Einträge zu löschen.
    //Dieser werden hier aus dem Array mittels der Entry-ID gelöscht.
    public function deleteEntry(TodoListItem $entry):void
    {
        if($this->loginUser->userId === $entry->creatorId &&
            array_key_exists($entry->entryId, $this->entries))
            unset($this->entries[$entry->entryId]);
        else throw new Exception("Entry " . $entry->entryId . " cant be deleted!");
    }

    //Es ist hier wieder nur für den Ersteller möglich, seine
    //Einträge zu bearbeiten. Er kann hier den Titel sowie
    //den Text seines Eintrages ändern. Zudem ändert sich
    //dann auch die Editor-ID und das Edit-Datum auf das
    //heutige Datum.
    public function editEntry(TodoListItem $entry, string $title, string $text):void
    {
        if($this->loginUser->userId === $entry->creatorId &&
            array_key_exists($entry->entryId, $this->entries)) {
            $obj = $this->entries[$entry->entryId];
            $obj->title = $title;
            $obj->text = $text;
            $obj->editorId = $this->loginUser->userId;
            $obj->editDate = new DateTime();
        }
        else throw new Exception("Entry " . $entry->entryId . " cant be edited!");
    }

    //Es ist hier wieder nur für den Ersteller möglich, seine
    //Einträge abzuschließen. Hier ändert sich der Status.
    public function finishEntry(TodoListItem $entry):void
    {
        if($this->loginUser->userId === $entry->creatorId &&
            array_key_exists($entry->entryId, $this->entries))
            $entry->status = "abgehakt";
        else throw new Exception("Entry " . $entry->entryId . " cant be finished!");
    }

    //Magic Method __toString() für die Ausgabe der
    //TodoListe für den gerade angemeldeten User
    public function __toString():string
    {
        $result="<input type='submit' class='add' value='+ neuen Eintrag hinzufügen'>";
        $result.="<div class='welcome'>\n
        <h1>ToDoList</h1>\n
        <h2>Willkommen ".$this->loginUser->userId."!</h2>\n
        </div>\n";
        $result.="<div class='toDoList'>\n";

        foreach($this->entries as $entry) {
            $result .= $entry;
            if ($entry->creatorId === $this->loginUser->userId) {
                $result .= "<div class='buttons'>\n<input type='submit' class='edit' value='Edit'>";
                $result .= "<input type='submit' class='delete' value='Delete'>";
                $result .= "<input type='submit' class='finish' value='Finish'>\n</div>";
            }
            $result .= "\n</div>";
        }

        $result .= "\n</div>";
        return $result;
    }
}