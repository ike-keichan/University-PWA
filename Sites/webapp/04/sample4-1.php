<!DOCTYPE html>
<html lang="ja">

<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <title>sample4-1</title>
</head>

<body>
    <?php
    for (print "A", $index = 0; print "B<br>", $index < 10; print "C", $index++) {
        echo $index;
    }
    ?>
</body>

</html>