<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Hausübung 6 - DOM/SAX Parser</title>
</head>
<body>
<?php
spl_autoload_register('autoload');
function autoload($sClassName){
    require_once("$sClassName.php");
}
//DOMPARSER
$oDomParser = new DomParser("quiz.xml");
if($oDomParser->load()){
    $oDomParser->output();
}
else echo("Document could not be loaded!");

//SAXPARSER
$saxParser = new SaxParser("quiz.xml");
$saxParser->parse();
?>
</body>
</html>