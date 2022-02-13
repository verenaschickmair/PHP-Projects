<?php


class SaxParser
{
    private string $file;
    private $parser;
    private string $sCurrentElement;
    private int $iCounter = 0;
    private string $sFirstName = ""; //Saves String
    private string $sDifficulty = ""; //Saves String
    private string $sType = ""; //Radio, Checkbox, Textbox
    private string $sDataType = ""; //String, Integer, ... for Textbox

    public function __construct(string $file){
        $this->file = $file;
        $this->parser = xml_parser_create();
        xml_set_object($this->parser, $this);
        xml_parser_set_option($this->parser, XML_OPTION_CASE_FOLDING, false); //dann nicht case-sensitive
        xml_parser_set_option($this->parser, XML_OPTION_SKIP_WHITE, true); //großer whitespace nicht berücksichtigen

        //Arbeitet Eventbasiert
        //Eventhandler mitteilen (bei Start- und Endevents):
        xml_set_element_handler($this->parser, "startElementHandler", "endElementHandler");

        //Bei unstruktierten Inhalt:
        xml_set_character_data_handler($this->parser, "characterDataHandler");
    }

    //Dokument wird nicht nur geladen wie bei DOMParser,
    //sondern der Parser führt selbstständig Dokument ganz durch
    //(in Reihenfolge von XML Dok), darum Funktion parse() statt load()
    public function parse(){
        if(!$handle = fopen($this->file, "r")){ //Modus r -> Öffnen zum Lesen
            return;
        }

        while($data = fread($handle, filesize($this->file))){
            if(!xml_parse($this->parser, $data, feof($handle))){
                $line = xml_get_current_line_number($this->parser);
                $errorCode = xml_get_error_code($this->parser);
                $errorText = xml_error_string($errorCode);
                echo("<b>Error at line ".$line.", code: ".$errorCode.
                    ", message: ".$errorText."</b><br>");
            }
        }

        fclose($handle);
        xml_parser_free($this->parser); //Speicherplatz freigeben
    }

    //Starttags
    private function startElementHandler($XMLParser, string $sElement, array $aAttributes){
        $this->sCurrentElement = $sElement;

        switch ($sElement){
            case "author":
                echo  ("<b>Ersteller*in: </b>");
                break;
            case "start":
                echo("<b>Online: </b> von ");
                break;
            case "end":
                echo("bis ");
                break;
            case "question":
                $this->iCounter++; //Counter for Question-Numbers
                echo("<br><b>".$this->iCounter.". </b>");
                //Save question type
                if(isset($aAttributes["type"])) {
                    //Textbox, Radiobuttons or Checkboxes
                    if ($aAttributes["type"] == "single")
                        $this->sType = "single";
                    else if ($aAttributes["type"] == "open")
                        $this->sType = "open";
                    else if ($aAttributes["type"] == "multiple")
                        $this->sType = "multiple";
                }
                //Sava data type of question
                if(isset($aAttributes["datatype"])){
                    $this->sDataType = $aAttributes["datatype"];
                }
                break;
            case "answer":
                echo("<br>");
                if($this->sType == "single")
                    echo("<input type='radio' name='answer" . $this->iCounter . "'>");
                else if($this->sType == "multiple")
                    echo("<input type='checkbox' name='answer" . $this->iCounter."'>");
                break;
            default: break;
        }
    }

    //Endtags
    private function endElementHandler($XMLParser, string $element){
        switch($element){
            case "lastname":
                echo(", ".$this->sFirstName);
                break;
            case "author":
                echo("<br>");
                echo("<b>Schwierigkeitsgrad: </b>".$this->sDifficulty."<br>");
                break;
            case "day":
                echo(".");
                break;
            case "month":
                echo(".");
                break;
            case "year":
                echo(" (");
                break;
            case "hours":
                echo(":");
                break;
            case "minutes":
                echo(")");
                break;
            case "end":
                echo("<br>");
                break;
            case "question":
                //If datatype available -> must stand next to the question
                if($this->sDataType != "")
                    echo("(bitte <b>".$this->sDataType."</b> eingeben)");
                //If textbox -> must stand next to the question
                if($this->sType == "open")
                    echo(" <input type='text' name='question".$this->iCounter."'>");
                echo("<br>");
                //Empty data for following question
                $this->sType = "";
                $this->sDataType = "";
                break;
            case "questions":
                echo("<br><br><input type='submit' value='Abschicken'>");
                break;
            default: break;
        }
    }

    //Textdata
    private function characterDataHandler($XMLParser, string $data){
        if($this->sCurrentElement == "title")
            echo("<h1>".$data."</h1>");
        else if($this->sCurrentElement == "firstname")
            $this->sFirstName .= $data;
        else if($this->sCurrentElement == "difficulty")
            $this->sDifficulty .= $data;
        else if($this->sCurrentElement == "text")
            echo("<b>".$data."</b>");
        else if($this->sCurrentElement == "answer"){
            if($this->sType == "single")
                echo("<label for='answer" . $this->iCounter . "'>" . $data . "</label>");
            else if($this->sType == "multiple")
                echo("<label for='" . $data . "'>" . $data . "</label>");
        }
        else echo($data);
    }
}