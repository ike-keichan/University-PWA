<!DOCTYPE html>
<html lang="ja">

<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <title>sql02</title>
</head>

<body>
    <?php
    try {
        $dbh = new PDO('sqlite:test.db', '', '');
        $sql = 'insert into list (url, contents) values (?, ?)';
        $sth = $dbh->prepare($sql);
        $url = "http://foo.jp/";
        $contents = "test data02";
        $sth->execute(array($url, $contents));

        $sth = $dbh->prepare("select * from list"); //prepareメソッドでSQL準備 
        $sth->execute(); //準備したSQL文の実行

        while ($row = $sth->fetch()) { //テーブルの内容を1行ずつ処理
            echo $row["url"] . $row["contents"] . "<br>";
        }
    } catch (PDOException $e) {
        print "エラー!: " . $e->getMessage() . "<br/>";
        die();
    }
    ?>
</body>

</html>