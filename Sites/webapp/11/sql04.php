<!DOCTYPE html>
<html lang="ja">

<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <title>sql04</title>
</head>

<body>
    <?php
    function insert_sql($table, $url, $contents)
    {
        try {
            $dbh = new PDO('sqlite:test.db', '', '');
            $sql = 'insert into list (url, contents) values (?, ?)';
            $sth = $dbh->prepare($sql);
            $sth->execute(array($url, $contents));

            $q = " '%t%' ";
            $sql = "select * from $table where contents like $q";
            $sth = $dbh->prepare($sql);
            $sth->execute();

            while ($row = $sth->fetch()) { //テーブルの内容を1行ずつ処理
                echo $row["url"] . $row["contents"] . "<br>";
            }
        } catch (PDOException $e) {
            print "エラー!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    $url = "http://www.kyoto-su.ac.jp/";
    $contents = "test data03";
    insert_sql("list", $url, $contents);
    ?>
</body>

</html>