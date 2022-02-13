<!doctype html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>HÜ 1 - Übung 1</title>
    <link rel="stylesheet" type="text/css" href="style.css"/>
</head>
<body>
<?php
require_once "util.php";

for($i = 99; $i > 0; $i--){
    writeLine($i, $i - 1);
}
?>
</body>
</html>
