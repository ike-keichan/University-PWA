<!DOCTYPE html>
<html lang="ja">

<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <title>sql01</title>
</head>

<body>
    <?php
    try {
        $dbh = new PDO("sqlite:test.db", ", "); //PDOクラスのオブジェクト作成 
        $sth = $dbh->prepare("select * from list"); //prepareメソッドでSQL準備 
        $sth->execute(); //準備したSQL文の実行

        while ($row = $sth->fetch()) { //テーブルの内容を1行ずつ処理 
            echo $row["id"] . $row["url"] . $row["contents"] . "<br>";
            echo $row[0] . $row[1] . $row[2] . "<br>";
        }
    } catch (PDOException $e) {
        print "エラー!: " . $e->getMessage() . "<br/>";
        die();
    }
    ?>
</body>

</html>