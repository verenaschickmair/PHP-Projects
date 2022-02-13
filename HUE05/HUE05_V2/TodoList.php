<?php


class TodoList
{
    public function __construct(
        private int $userId,
        private string $username,
        public array $entries = []
    ) {
    }

    //Getter
    public function __get(string $entries):mixed
    {
        if (property_exists('TodoList', $entries)) {
            return $this->{$entries};
        } else throw new Exception("Attribute " . $entries . " does not exist in class TodoList!");
    }

    //Setter
    public function __set(string $entries, mixed $value):void
    {
        if (property_exists('TodoList', $entries)) {
            $this->{$entries} = $value;
        } else throw new Exception("Attribute " . $entries . " does not exist in class TodoList!");
    }

    //Magic Method __toString() für die Ausgabe der
    //TodoListe für den gerade angemeldeten User
    public function __toString():string
    {
        $result="<div class='welcome'>\n
        <h1>DEINE TO-DO LISTE</h1>\n
        <h2>Hallo " . $this->username . "! (ID: ".$this->userId.")</h2>\n
        </div>\n";
        $result.="<div class='toDoList'>\n";

        foreach($this->entries as $entry) {
                $result .= $entry;
        }

        $result .= "\n</div>";
        return $result;
    }
}