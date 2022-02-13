<?php


class DomParser{
    private string $sFile;
    private DomDocument $oDoc;

    public function __construct(string $sFile){
        $this->sFile = $sFile;
        $this->oDoc = new DOMDocument();
    }

    public function load():bool{
        return ($this->oDoc->load($this->sFile));
    }

    public function output():void
    {
        $sOutput = "";
        $oElementsTitle = $this->oDoc->getElementsByTagName("title");

        //TITLE
        foreach ($oElementsTitle as $oTitle) {
            $sOutput .= "<h1>" . $oTitle->nodeValue . "</h1>";
        }

        //AUTHOR
        $oElementsAuthor = $this->oDoc->getElementsByTagName("author");
        foreach ($oElementsAuthor as $oAuthor) {
            $oInfoAuthor = $oAuthor->childNodes;
            $sTempInfo = "";
            foreach ($oInfoAuthor as $oInfoItem) {
                $sCurrentNodeName = $oInfoItem->nodeName;
                if ($sCurrentNodeName == "firstname") {
                    $sTempInfo .= $oInfoItem->nodeValue . "<br>";
                } else if ($sCurrentNodeName == "lastname") {
                    $sOutput .= "<b>Ersteller*in: </b>" . $oInfoItem->nodeValue . ", ";
                }
            }
            $sOutput .= $sTempInfo;
        }

        //DIFFICULTY
        $oElementsDiff = $this->oDoc->getElementsByTagName("difficulty");
        foreach ($oElementsDiff as $oDiff) {
            $sOutput .= "<b>Schwierigkeitsgrad: </b>" . $oDiff->nodeValue . "<br>";
        }

        //ONLINE
        //START
        $oElementsStart = $this->oDoc->getElementsByTagName("start");
        foreach ($oElementsStart as $oStart) {
            $sOutput .= "<b>Online:</b> von ";
            $oInfoStart = $oStart->childNodes;
            foreach ($oInfoStart as $oInfoItem) {
                $sCurrentNodeName = $oInfoItem->nodeName;
                if ($sCurrentNodeName == "date") {
                    $oDate = $oInfoItem->childNodes;
                    foreach ($oDate as $oDateItem) {
                        $sCurrentNodeNameDate = $oDateItem->nodeName;
                        if ($sCurrentNodeNameDate == "day") {
                            $sOutput .= $oDateItem->nodeValue . ".";
                        } else if ($sCurrentNodeNameDate == "month") {
                            $sOutput .= $oDateItem->nodeValue . ".";
                        } else if ($sCurrentNodeNameDate == "year") {
                            $sOutput .= $oDateItem->nodeValue . " ";
                        }
                    }
                } else if ($sCurrentNodeName == "time") {
                    $oTime = $oInfoItem->childNodes;
                    foreach ($oTime as $oTimeItem) {
                        $sCurrentNodeNameTime = $oTimeItem->nodeName;
                        if ($sCurrentNodeNameTime == "hours") {
                            $sOutput .= "(" . $oTimeItem->nodeValue . ":";
                        } else if ($sCurrentNodeNameTime == "minutes") {
                            $sOutput .= $oTimeItem->nodeValue . ") ";
                        }
                    }
                }
            }
        }

        //END
        $oElementsEnd = $this->oDoc->getElementsByTagName("end");
        foreach ($oElementsEnd as $oEnd) {
            $oInfoEnd = $oEnd->childNodes;
            foreach ($oInfoEnd as $oInfoItem) {
                $sCurrentNodeName = $oInfoItem->nodeName;
                if ($sCurrentNodeName == "date") {
                    $oDate = $oInfoItem->childNodes;
                    foreach ($oDate as $oDateItem) {
                        $sCurrentNodeNameDate = $oDateItem->nodeName;
                        if ($sCurrentNodeNameDate == "day") {
                            $sOutput .= "bis " . $oDateItem->nodeValue . ".";
                        } else if ($sCurrentNodeNameDate == "month") {
                            $sOutput .= $oDateItem->nodeValue . ".";
                        } else if ($sCurrentNodeNameDate == "year") {
                            $sOutput .= $oDateItem->nodeValue . " ";
                        }
                    }
                } else if ($sCurrentNodeName == "time") {
                    $oTime = $oInfoItem->childNodes;
                    foreach ($oTime as $oTimeItem) {
                        $sCurrentNodeNameTime = $oTimeItem->nodeName;
                        if ($sCurrentNodeNameTime == "hours") {
                            $sOutput .= "(" . $oTimeItem->nodeValue . ":";
                        } else if ($sCurrentNodeNameTime == "minutes") {
                            $sOutput .= $oTimeItem->nodeValue . ")<br><br>";
                        }
                    }
                }
            }
        }

        //QUESTIONS ELEMENT
        $oElementsQuestions = $this->oDoc->getElementsByTagName("questions");
        foreach ($oElementsQuestions as $oQuestions) {

            //QUESTION ELEMENT + COUNTER FOR QUESTIONS
            $oElementsQuestion = $this->oDoc->getElementsByTagName("question");
            $iCounter = 0;

            foreach ($oElementsQuestion as $oQuestion) {
                $iCounter++;
                if ($oQuestion->hasAttribute("type")) {
                    $sInputType = $oQuestion->getAttribute("type");
                }
                $oQuestionInfos = $oQuestion->childNodes;

                //QUESTION-TEXT AND ANSWERS
                foreach ($oQuestionInfos as $oQuestionInfo) {
                    $sCurrentNodeName = $oQuestionInfo->nodeName;

                    //QUESTION AND INPUT FIELD FOR OPEN INPUT
                    if ($sCurrentNodeName == "text" && $oQuestion->hasAttribute("correctanswer")) {
                        $sOutput .= "<b>" . $iCounter . ". <label for='" . $oQuestion->getAttribute("correctanswer") . "'>" . $oQuestionInfo->nodeValue . "</label></b>";
                        if ($oQuestion->hasAttribute("datatype"))
                            $sOutput .= " (bitte <b>" . $oQuestion->getAttribute("datatype") . "</b> eingeben) ";
                        $sOutput .= "<input type='text' name='" . $oQuestion->getAttribute("correctanswer") . "'><br>";

                    } //QUESTION FOR RADIO/CHECKBOXES
                    else if ($sCurrentNodeName == "text") {
                        $sOutput .= "<b>" . $iCounter . ". " . $oQuestionInfo->nodeValue . "</b>";
                        if ($oQuestion->hasAttribute("datatype"))
                            $sOutput .= " (bitte <b>" . $oQuestion->getAttribute("datatype") . "</b> eingeben) ";
                        $sOutput .= "<br>";
                    }
                    //RADIO/CHECKBOXES
                    if ($sCurrentNodeName == "answer") {
                        if ($sInputType == "single") {
                            $sOutput .= "<input type='radio' value='" . $oQuestionInfo->nodeValue . "' name='answer" . $iCounter . "'>";
                            $sOutput .= "<label for='answer" . $iCounter . "'>" . $oQuestionInfo->nodeValue . "</label><br>";
                        } else if ($sInputType == "multiple") {
                            $sOutput .= "<input type='checkbox' value='" . $oQuestionInfo->nodeValue . "' name='" . $oQuestionInfo->nodeValue . "'>";
                            $sOutput .= "<label for='" . $oQuestionInfo->nodeValue . "'>" . $oQuestionInfo->nodeValue . "</label><br>";
                        }
                    }
                }
                $sOutput .= "<br>";
            }
        }
        $sOutput .= "<br><input type='submit' value='Abschicken'>";
        echo($sOutput);
    }
}

?>