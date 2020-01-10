<!DOCTYPE html>
<html lang="ja">

<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <title>ensyu4-2</title>
</head>

<body>
    <?php
    $month = array("Oct" => 10, "Nov" => 11, "Dec" => 12);
    $week = array(0 => "月", 1 => "火", 2 => "水");
    print "今日は" . $month["Oct"] . "月の" . $week[0] . "曜日です。";
    ?>
</body>

</html>