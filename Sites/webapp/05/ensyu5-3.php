<!DOCTYPE html>
<html lang="ja">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <title>ensyu5-3</title>
</head>
<body>
    <?php
        function drink(&$a) { 
            $a="coffee";
        } 
        $cup = "tea"; 
        print " $cup <br> "; 
        drink($cup);
        print " $cup <br> ";
    ?>
</body>
</html>