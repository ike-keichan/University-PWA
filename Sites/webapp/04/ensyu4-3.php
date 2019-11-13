<!DOCTYPE html>
<html lang="ja">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <title>ensyu4-3</title>
</head>
<body>
    <?php
        $week = array(0 => "月", 1 => "火", 2=> "水", 3=> "木", 4=> "金", 5=> "土", 6=> "日"); 
        print "1週間は、";
        foreach ( $week as $value){
            print $value."、"; 
            $count++;
        }
        
        print "の".count($week)."日間です。";
    ?>
</body>
</html>