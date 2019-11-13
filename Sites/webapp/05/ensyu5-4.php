<!DOCTYPE html>
<html lang="ja">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <title>ensyu5-4</title>
</head>
<body>
    <?php
        function swap(&$a, &$b) { 
            $stock = $a;
            $a = $b;
            $b = $stock;
        } 
        $aCup = "tea"; 
        $anotherCup = "coffee";
        print " $aCup <br> "; 
        print " $anotherCup <br>";
        swap($aCup, $anotherCup);
        print " -----swap!-----<br>";
        print " $aCup <br> "; 
        print " $anotherCup <br>";
    ?>
</body>
</html>