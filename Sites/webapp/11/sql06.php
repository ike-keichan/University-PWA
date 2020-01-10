<!DOCTYPE html>
<html lang="ja">

<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <title>sql06</title>
</head>

<body>
    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
        <p>
            <input type="text" name="query" placeholder="URL" />
            <input type="text" name="key" placeholder="SQL検索" />
            <input type="submit" value="送信" />
        </p>
    </form>
    <?php

    $q_query = $_POST["query"];
    $q_key = $_POST["key"];
    if (isset($q_query) && isset($q_key)) {
        echo "<h1>" . $q_query . "</h1><hr>";
        echo "<h1>" . $q_key . "</h1><hr>";

        $query = $q_query;
        $key = $q_key;

        function httpRequest($url)
        {
            $purl = parse_url($url);
            $psheme = $purl["scheme"];
            $phost = $purl["host"];

            if (!isset($purl["port"])) {
                $pport = 80;
            } else {
                $pport = $purl["port"];
            }

            if (!isset($purl["path"])) {
                $ppath = "/";
            } else {
                $ppath = $purl["path"];
            }

            echo "プロトコル：" . $psheme . "<br>";
            echo "ホスト名：" . $phost . "<br>";
            echo "ポート：" . $pport . "<br>";
            echo "パス：" . $ppath . "<br> <hr>";

            $hostname = $phost;
            $fp = fsockopen($hostname, $pport, $errno, $errstr);
            socket_set_timeout($fp, 10);

            $request = "GET " . $ppath . " HTTP/1.0\r\n\r\n";
            fputs($fp, $request);

            $response = "";
            while (!feof($fp)) {
                $response .= fgets($fp, 4096);
            }

            fclose($fp);

            $DATA = explode("\r\n\r\n", $response, 2);
            return $DATA;
        }

        function insert_sql($table, $url, $contents, $key)
        {
            try {
                $dbh = new PDO('sqlite:test.db', '', '');
                $sql = 'insert into list (url, contents) values (?, ?)';
                $sth = $dbh->prepare($sql);
                $sth->execute(array($url, $contents));

                $q = " '%$key%' ";
                $sql = "select * from $table where contents like $q";
                $sth = $dbh->prepare($sql);
                $sth->execute();

                $sql = "select count(*) from $table where contents like $q";
                $sth = $dbh->prepare($sql);
                $sth->execute();

                $cnt = $sth->fetchColumn();
                echo $cnt . "<br>";

                while ($row = $sth->fetch()) { //テーブルの内容を1行ずつ処理
                    echo $row["url"] . $row["contents"] . "<br>";
                }
            } catch (PDOException $e) {
                print "エラー!: " . $e->getMessage() . "<br/>";
                die();
            }
        }

        $url = $q_query;
        $contents = "test data05";
        insert_sql("list", $url, $contents, $key);

        list($head, $data) = httpRequest($q_query);
        echo $data;
    }


    ?>
</body>

</html>