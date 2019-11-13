<!DOCTYPE html>
<html lang="ja">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <title>report6-1</title>
</head>
<body>
    <h1>実践Webアプリケーション レポート6-1</h1>
    <p>ーーーーーーーーーーーーーーーーーーーー</p>
    <p>
        学生証番号:g1744069<br>
        氏名:池田 敬祐<br>
        課題:１から n までの整数の和を返す関数 sum を作れ．for 文を使う．和の公式を使わずに，ここでは1 から n までの値を足し合わせるように書くこと.
    </p>
    <p>ーーーーーーーーーーーーーーーーーーーー</p>
    </p><br>
    <a>実行結果</a><br>
    <?php
        function sum($number) { 
            $outputNumber = 0;
            for ($index = 0; $index <= $number; $index++){
                 $outputNumber += $index;
            }
            return $outputNumber; 
        }

        $sumNumber = sum(10);
        print "sum：$sumNumber";

    ?>
</body>
</html>