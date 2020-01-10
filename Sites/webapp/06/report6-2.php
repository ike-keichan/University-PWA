<!DOCTYPE html>
<html lang="ja">

<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <title>report6-2</title>
</head>

<body>
    <h1>実践Webアプリケーション レポート6-2</h1>
    <p>ーーーーーーーーーーーーーーーーーーーー</p>
    <p>
        学生証番号:g1744069<br>
        氏名:池田 敬祐<br>
        課題:配列内の最小値を返す関数 arrayMinを作れ．
    </p>
    <p>ーーーーーーーーーーーーーーーーーーーー</p>
    </p><br>
    <a>実行結果</a><br>
    <?php
    function arrayMin($inputArray)
    {
        return min($inputArray);
    }

    $anArray = array(
        0 => 10,
        1 => 20,
        2 => 30,
        3 => 7,
        4 => 12,
        5 => 8
    );
    $b = arrayMin($anArray);
    print "配列：";
    print_r($anArray);
    print "<br>min：$b<br>";

    ?>
</body>

</html>