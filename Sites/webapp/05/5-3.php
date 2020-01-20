<!DOCTYPE html>
<html lang="ja">

<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <title>5-3</title>
</head>

<body>
    <?php
    function drink(&$a_drink)
    {
        $a_drink = "coffee";
    }
    $cup = "tea";
    print " $cup <br> ";
    drink($cup);
    print " $cup <br> ";
    ?>
</body>

</html>