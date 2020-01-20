<!DOCTYPE html>
<html lang="ja">

<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <title>5-1</title>
</head>

<body>
    <?php
    $x = 2; //動作確認用
    function power($x, $number)
    {
        $y = 1.0;
        for ($index = 0; $index < $number; $index++) {
            $y *= $x;
        }
        return $y;
    }
    $y = power($x, 3);
    $z = power($y + $x, 2);
    print "$y <hr> $z <br> ";
    ?>
</body>

</html>