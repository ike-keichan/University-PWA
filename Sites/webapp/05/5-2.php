<!DOCTYPE html>
<html lang="ja">

<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <title>ensyu5-2</title>
</head>

<body>
    <?php
    function printDate($years, $months, $day)
    {
        print " $years 年 $months 月 $day 日<br> ";
    }
    $day = 1;
    printDate(2010, 10, $day);
    ?>
</body>

</html>